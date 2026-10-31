<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Enquiry;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Models\User;
use App\Support\EnquirySpamFilter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class EnquirySpamFilterTest extends TestCase
{
    use RefreshDatabase;

    private const PROMOTIONAL_MESSAGE = "Hi,\n\nI just visited suryaasolarsolution.com and wondered if you've ever "
        ."considered an impactful video to advertise your business?\n\nOur prices start from just $195 (USD) / £145.";

    /**
     * @return array<string, array{string, string}>
     */
    public static function spamProvider(): array
    {
        return [
            'promotional message from an Indian number' => ['9876543210', self::PROMOTIONAL_MESSAGE],
            'foreign number without a prefix' => ['4912302810', 'Please share the price.'],
            'foreign number with a country code' => ['+1 491 230 2810', 'Please share the price.'],
            'link in the message' => ['9876543210', 'Visit https://example.com'],
            'seo pitch' => ['9876543210', 'We offer SEO services.'],
            'foreign price' => ['9876543210', 'Only $50 a month.'],
        ];
    }

    /**
     * @return array<string, array{string, string}>
     */
    public static function genuineProvider(): array
    {
        return [
            'mobile' => ['98765-43210', 'Need a 200 LPD water heater.'],
            'mobile with country code' => ['+91 98765 43210', 'Please share the price.'],
            'mobile with leading zero' => ['09876543210', 'Please share the price.'],
            'landline with STD code' => ['01892-222222', 'Please call about a rooftop system.'],
            'landline with country code' => ['+91 1892 222222', 'Please call about a rooftop system.'],
            'mentions the website and rupees' => ['9876543210', 'Saw your website. Is ₹50000 possible for my business?'],
            'word containing a blocked phrase' => ['9876543210', 'Please visit Seoni village site.'],
        ];
    }

    #[DataProvider('spamProvider')]
    public function test_it_flags_spam(string $phone, string $message): void
    {
        $this->assertTrue(EnquirySpamFilter::isSpam($phone, $message));
    }

    #[DataProvider('genuineProvider')]
    public function test_it_allows_genuine_enquiries(string $phone, string $message): void
    {
        $this->assertFalse(EnquirySpamFilter::isSpam($phone, $message));
    }

    /**
     * @return array<string, array{string}>
     */
    public static function formRouteProvider(): array
    {
        return [
            'contact form' => ['contact.store'],
            'enquiry form' => ['enquiry.store'],
        ];
    }

    #[DataProvider('formRouteProvider')]
    public function test_spam_is_kept_for_review_without_emailing_anyone(string $routeName): void
    {
        Mail::fake();
        SiteSetting::create(['site_name' => 'Suryaa', 'email' => 'owner@example.com']);

        $this->post(route($routeName), [
            'name' => 'Joanna Riggs',
            'phone' => '4912302810',
            'email' => 'joanna@example.com',
            'message' => self::PROMOTIONAL_MESSAGE,
        ])->assertRedirect();

        Mail::assertNothingOutgoing();
        $this->assertDatabaseHas('enquiries', ['name' => 'Joanna Riggs', 'status' => Enquiry::STATUS_SPAM]);
    }

    #[DataProvider('formRouteProvider')]
    public function test_genuine_enquiry_is_saved_as_pending(string $routeName): void
    {
        Mail::fake();

        $this->post(route($routeName), [
            'name' => 'Asha Devi',
            'phone' => '98765-43210',
            'message' => 'Need a 200 LPD water heater.',
        ])->assertRedirect();

        $this->assertDatabaseHas('enquiries', ['name' => 'Asha Devi', 'status' => Enquiry::STATUS_PENDING]);
    }

    public function test_product_link_added_to_an_enquiry_does_not_make_it_spam(): void
    {
        Mail::fake();

        $category = Category::create(['name' => 'Water Heaters', 'slug' => 'water-heaters']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Solar Heater',
            'slug' => 'solar-heater',
            'price' => 25000,
            'is_active' => true,
        ]);

        $this->post(route('enquiry.store'), [
            'name' => 'Asha Devi',
            'phone' => '9876543210',
            'message' => 'Please share the price.',
            'product_id' => $product->id,
        ])->assertRedirect();

        $enquiry = Enquiry::sole();

        $this->assertStringContainsString('http', $enquiry->message);
        $this->assertSame(Enquiry::STATUS_PENDING, $enquiry->status);
    }

    public function test_contact_form_rejects_a_phone_with_letters(): void
    {
        $this->post(route('contact.store'), [
            'name' => 'Bot',
            'phone' => 'call-me-now',
            'message' => 'Hello',
        ])->assertSessionHasErrors('phone');

        $this->assertDatabaseCount('enquiries', 0);
    }

    public function test_contact_form_is_rate_limited(): void
    {
        Mail::fake();

        $enquiry = ['name' => 'Asha Devi', 'phone' => '9876543210', 'message' => 'Need a water heater.'];

        for ($attempt = 0; $attempt < 10; $attempt++) {
            $this->post(route('contact.store'), $enquiry)->assertRedirect();
        }

        $this->post(route('contact.store'), $enquiry)->assertTooManyRequests();
    }

    public function test_admin_sees_spam_only_in_the_spam_folder(): void
    {
        $this->actingAs($this->admin());

        Enquiry::create(['name' => 'Asha Devi', 'phone' => '9876543210', 'message' => 'Hello']);
        Enquiry::create(['name' => 'Joanna Riggs', 'phone' => '4912302810', 'message' => 'Video', 'status' => Enquiry::STATUS_SPAM]);

        $this->get(route('admin.enquiries.index'))
            ->assertOk()
            ->assertSee('Asha Devi')
            ->assertDontSee('Joanna Riggs')
            ->assertSee('Spam (1)');

        $this->get(route('admin.enquiries.index', ['view' => 'spam']))
            ->assertOk()
            ->assertSee('Joanna Riggs')
            ->assertDontSee('Asha Devi');
    }

    public function test_admin_can_move_a_message_back_out_of_spam(): void
    {
        $this->actingAs($this->admin());

        $enquiry = Enquiry::create(['name' => 'Asha Devi', 'phone' => '+44 20 7946 0958', 'message' => 'Hello', 'status' => Enquiry::STATUS_SPAM]);

        $this->patch(route('admin.enquiries.update-status', $enquiry), ['status' => Enquiry::STATUS_PENDING])->assertRedirect();

        $this->assertSame(Enquiry::STATUS_PENDING, $enquiry->fresh()->status);
    }

    private function admin(): User
    {
        return User::factory()->create()->forceFill(['is_admin' => true]);
    }
}
