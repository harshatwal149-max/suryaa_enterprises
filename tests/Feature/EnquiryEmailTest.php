<?php

namespace Tests\Feature;

use App\Mail\NewEnquiryMail;
use App\Models\Enquiry;
use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use RuntimeException;
use Tests\TestCase;

class EnquiryEmailTest extends TestCase
{
    use RefreshDatabase;

    private array $enquiry = [
        'name' => 'Asha Devi',
        'phone' => '98765-43210',
        'email' => 'asha@example.com',
        'message' => 'Need a 200 LPD water heater.',
    ];

    public function test_enquiry_form_emails_the_business_address_from_settings(): void
    {
        Mail::fake();
        SiteSetting::create(['site_name' => 'Suryaa', 'email' => 'owner@example.com']);

        $this->post(route('enquiry.store'), $this->enquiry)->assertRedirect();

        Mail::assertSent(NewEnquiryMail::class, fn (NewEnquiryMail $mail) => $mail->hasTo('owner@example.com')
            && $mail->enquiry->is(Enquiry::first())
            && $mail->source === 'Enquiry form');
    }

    public function test_contact_form_emails_the_business_address_from_settings(): void
    {
        Mail::fake();
        SiteSetting::create(['site_name' => 'Suryaa', 'email' => 'owner@example.com']);

        $this->post(route('contact.store'), $this->enquiry + ['interest' => 'Rooftop solar'])->assertRedirect();

        Mail::assertSent(NewEnquiryMail::class, fn (NewEnquiryMail $mail) => $mail->hasTo('owner@example.com')
            && str_contains($mail->enquiry->message, 'Interested in: Rooftop solar')
            && $mail->source === 'Contact Us page');
    }

    public function test_email_falls_back_to_the_from_address_without_a_business_email(): void
    {
        Mail::fake();
        config(['mail.from.address' => 'fallback@example.com']);

        $this->post(route('enquiry.store'), $this->enquiry);

        Mail::assertSent(NewEnquiryMail::class, fn (NewEnquiryMail $mail) => $mail->hasTo('fallback@example.com'));
    }

    public function test_honeypot_submission_sends_no_email(): void
    {
        Mail::fake();

        $this->post(route('enquiry.store'), $this->enquiry + ['website' => 'http://spam.example']);

        Mail::assertNothingSent();
        $this->assertDatabaseCount('enquiries', 0);
    }

    public function test_mail_failure_still_saves_the_enquiry_and_succeeds(): void
    {
        Mail::shouldReceive('to')->once()->andThrow(new RuntimeException('SMTP down'));
        Log::shouldReceive('error')->once();

        $this->post(route('enquiry.store'), $this->enquiry)->assertRedirect()->assertSessionHas('success');

        $this->assertDatabaseHas('enquiries', ['email' => 'asha@example.com']);
    }
}
