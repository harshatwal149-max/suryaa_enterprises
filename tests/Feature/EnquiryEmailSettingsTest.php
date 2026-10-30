<?php

namespace Tests\Feature;

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class EnquiryEmailSettingsTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['is_admin' => true]);
    }

    public function test_admin_can_save_multiple_enquiry_emails(): void
    {
        $this->actingAs($this->admin())
            ->put(route('admin.settings.contact.update'), [
                'enquiry_emails' => "Owner@example.com, sales@example.com\nowner@example.com",
            ])
            ->assertRedirect(route('admin.settings.contact'))
            ->assertSessionHasNoErrors();

        $this->assertSame(['owner@example.com', 'sales@example.com'], SiteSetting::first()->enquiry_emails);
    }

    public function test_contact_page_shows_saved_enquiry_emails_one_per_line(): void
    {
        SiteSetting::create(['site_name' => 'Suryaa', 'enquiry_emails' => ['owner@example.com', 'sales@example.com']]);

        $this->actingAs($this->admin())
            ->get(route('admin.settings.contact'))
            ->assertOk()
            ->assertSee("owner@example.com\nsales@example.com");
    }

    public function test_clearing_the_list_falls_back_to_the_business_email(): void
    {
        $this->actingAs($this->admin())
            ->put(route('admin.settings.contact.update'), ['email' => 'owner@example.com', 'enquiry_emails' => ''])
            ->assertSessionHasNoErrors();

        $this->assertSame(['owner@example.com'], SiteSetting::first()->enquiryRecipients());
    }

    public function test_invalid_addresses_are_rejected_by_name(): void
    {
        $response = $this->actingAs($this->admin())
            ->from(route('admin.settings.contact'))
            ->put(route('admin.settings.contact.update'), ['enquiry_emails' => 'owner@example.com, not-an-email']);

        $response->assertSessionHasErrors(['enquiry_emails.1' => '"not-an-email" is not a valid email address.']);
    }

    public function test_more_than_five_addresses_are_rejected(): void
    {
        $emails = collect(range(1, 6))->map(fn (int $number) => "user{$number}@example.com")->implode(',');

        $this->actingAs($this->admin())
            ->from(route('admin.settings.contact'))
            ->put(route('admin.settings.contact.update'), ['enquiry_emails' => $emails])
            ->assertSessionHasErrors('enquiry_emails');
    }

    public function test_enquiries_page_warns_about_a_delayed_email(): void
    {
        DB::table('jobs')->insert([
            'queue' => 'default',
            'payload' => '{"displayName":"App\\\\Mail\\\\NewEnquiryMail"}',
            'attempts' => 0,
            'available_at' => now()->getTimestamp(),
            'created_at' => now()->subMinutes(11)->getTimestamp(),
        ]);

        $this->actingAs($this->admin())
            ->get(route('admin.enquiries.index'))
            ->assertSee('1 enquiry email has not been delivered yet');
    }

    public function test_enquiries_page_has_no_warning_when_mail_is_healthy(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.enquiries.index'))
            ->assertDontSee('not been delivered yet');
    }

    public function test_guests_cannot_change_enquiry_emails(): void
    {
        $this->put(route('admin.settings.contact.update'), ['enquiry_emails' => 'attacker@example.com'])
            ->assertRedirect();

        $this->assertNull(SiteSetting::first());
    }
}
