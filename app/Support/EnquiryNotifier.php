<?php

namespace App\Support;

use App\Mail\NewEnquiryMail;
use App\Models\Enquiry;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Emails new enquiries to the recipients set in Admin → Contact Details.
 */
class EnquiryNotifier
{
    /** Queued emails older than this are reported as delayed in Admin. */
    private const DELAYED_AFTER_MINUTES = 10;

    /**
     * Queues one email per recipient; the queue worker delivers and retries them.
     * Best-effort: the enquiry is already saved, so a mail failure must not fail the form.
     */
    public static function send(Enquiry $enquiry, string $source): void
    {
        $settings = SiteSetting::first();
        $recipients = $settings?->enquiryRecipients() ?: array_filter([config('mail.from.address')]);

        foreach ($recipients as $recipient) {
            try {
                Mail::to($recipient)->send(new NewEnquiryMail($enquiry, $source, $settings));
            } catch (\Throwable $e) {
                Log::error('Enquiry #'.$enquiry->id.' email to '.$recipient.' could not be queued: '.$e->getMessage());
            }
        }
    }

    /**
     * Enquiry emails that gave up, or have waited too long in the queue.
     */
    public static function undeliveredCount(): int
    {
        $failed = DB::table('failed_jobs')->where('payload', 'like', '%NewEnquiryMail%')->count();

        $delayed = DB::table('jobs')
            ->where('payload', 'like', '%NewEnquiryMail%')
            ->where('created_at', '<', now()->subMinutes(self::DELAYED_AFTER_MINUTES)->getTimestamp())
            ->count();

        return $failed + $delayed;
    }
}
