<?php

namespace App\Support;

use App\Mail\NewEnquiryMail;
use App\Models\Enquiry;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Emails new enquiries to the recipients set in Admin → Contact Details.
 */
class EnquiryNotifier
{
    /**
     * Best-effort: the enquiry is already saved, so a mail failure must not fail the form.
     * Each recipient gets a separate email, so one bad address cannot block the others.
     */
    public static function send(Enquiry $enquiry, string $source): void
    {
        $settings = SiteSetting::first();
        $recipients = $settings?->enquiryRecipients() ?: array_filter([config('mail.from.address')]);

        foreach ($recipients as $recipient) {
            try {
                Mail::to($recipient)->send(new NewEnquiryMail($enquiry, $source, $settings));
            } catch (\Throwable $e) {
                Log::error('Enquiry #'.$enquiry->id.' email to '.$recipient.' failed: '.$e->getMessage());
            }
        }
    }
}
