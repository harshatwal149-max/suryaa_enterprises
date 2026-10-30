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

    public function test_each_enquiry_recipient_gets_an_email_instead_of_the_business_email(): void
    {
        Mail::fake();
        SiteSetting::create([
            'site_name' => 'Suryaa',
            'email' => 'owner@example.com',
            'enquiry_emails' => ['sales@example.com', 'manager@example.com'],
        ]);

        $this->post(route('enquiry.store'), $this->enquiry);

        Mail::assertSentCount(2);
        Mail::assertSent(NewEnquiryMail::class, fn (NewEnquiryMail $mail) => $mail->hasTo('sales@example.com'));
        Mail::assertSent(NewEnquiryMail::class, fn (NewEnquiryMail $mail) => $mail->hasTo('manager@example.com'));
    }

    public function test_one_failed_recipient_does_not_block_the_others(): void
    {
        SiteSetting::create(['site_name' => 'Suryaa', 'enquiry_emails' => ['bad@example.com', 'good@example.com']]);

        Mail::shouldReceive('to')->once()->with('bad@example.com')->andThrow(new RuntimeException('Rejected'));
        Mail::shouldReceive('to')->once()->with('good@example.com')->andReturnSelf();
        Mail::shouldReceive('send')->once();
        Log::shouldReceive('error')->once();

        $this->post(route('enquiry.store'), $this->enquiry)->assertRedirect();
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
