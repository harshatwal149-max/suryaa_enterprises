<?php

namespace App\Support;

use App\Mail\NewEnquiryMail;
use App\Models\Enquiry;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Emails new enquiries to the business email set in Admin → Settings.
 */
class EnquiryNotifier
{
    /**
     * Best-effort: the enquiry is already saved, so a mail failure must not fail the form.
     */
    public static function send(Enquiry $enquiry, string $source): void
    {
        $settings = SiteSetting::first();
        $recipient = $settings?->email ?: config('mail.from.address');

        if (! $recipient) {
            return;
        }

        try {
            Mail::to($recipient)->send(new NewEnquiryMail($enquiry, $source, $settings));
        } catch (\Throwable $e) {
            Log::error('Enquiry #'.$enquiry->id.' email failed: '.$e->getMessage());
        }
    }
}
