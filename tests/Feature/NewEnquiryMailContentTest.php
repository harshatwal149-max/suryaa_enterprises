<?php

namespace Tests\Feature;

use App\Mail\NewEnquiryMail;
use App\Models\Enquiry;
use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewEnquiryMailContentTest extends TestCase
{
    use RefreshDatabase;

    private function mail(array $attributes = []): NewEnquiryMail
    {
        $enquiry = Enquiry::create($attributes + [
            'name' => 'Asha Devi',
            'phone' => '98765-43210',
            'email' => 'asha@example.com',
            'message' => "Line one\nLine two",
        ]);

        return new NewEnquiryMail($enquiry, 'Enquiry form', new SiteSetting(['site_name' => 'Suryaa']));
    }

    public function test_it_shows_all_enquiry_details_in_html_and_text(): void
    {
        $mail = $this->mail();

        $mail->assertHasSubject('New enquiry from Asha Devi');
        $mail->assertSeeInOrderInHtml(['Asha Devi', '98765-43210', 'asha@example.com', 'Line one', 'Line two']);
        $mail->assertSeeInText('asha@example.com');
        $mail->assertSeeInText('Enquiry form');
        $mail->assertSeeInHtml('https://wa.me/919876543210');
        $mail->assertSeeInHtml('tel:98765'.'43210');
    }

    public function test_replies_go_to_the_customer(): void
    {
        $this->mail()->assertHasReplyTo('asha@example.com', 'Asha Devi');
    }

    public function test_it_handles_a_customer_without_email(): void
    {
        $mail = $this->mail(['email' => null]);

        $mail->assertSeeInHtml('Not provided');
        $this->assertFalse($mail->hasReplyTo('asha@example.com'));
    }

    public function test_it_escapes_customer_input(): void
    {
        $mail = $this->mail(['name' => '<b>Bold</b>', 'message' => '<script>alert(1)</script>']);

        $mail->assertDontSeeInHtml('<script>alert(1)</script>', false);
        $mail->assertDontSeeInHtml('<b>Bold</b>', false);
    }
}
