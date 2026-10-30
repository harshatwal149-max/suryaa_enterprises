<?php

namespace App\Mail;

use App\Models\Enquiry;
use App\Models\SiteSetting;
use DateTimeInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class NewEnquiryMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Seconds to wait before each retry; the last value repeats until retryUntil().
     *
     * @var array<int, int>
     */
    public array $backoff = [60, 300, 900, 3600];

    /**
     * An enquiry deleted in Admin no longer needs its email.
     */
    public bool $deleteWhenMissingModels = true;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Enquiry $enquiry,
        public string $source,
        public ?SiteSetting $settings = null,
    ) {}

    /**
     * Keep retrying through long SMTP outages before giving up.
     */
    public function retryUntil(): DateTimeInterface
    {
        return now()->addDays(3);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New enquiry from '.$this->enquiry->name,
            replyTo: filled($this->enquiry->email)
                ? [new Address($this->enquiry->email, $this->enquiry->name)]
                : [],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $whatsappDigits = $this->enquiry->whatsappDigits();
        $logoPath = $this->settings?->logo_path;

        return new Content(
            view: 'emails.new-enquiry',
            text: 'emails.new-enquiry-text',
            with: [
                'siteName' => $this->settings?->site_name ?: config('app.name'),
                'primaryColor' => $this->settings?->primary_color ?: '#047857',
                'accentColor' => $this->settings?->secondary_color ?: '#e1c32d',
                'logoUrl' => $logoPath && Storage::disk('public')->exists($logoPath)
                    ? asset('storage/'.$logoPath)
                    : null,
                'receivedAt' => $this->enquiry->created_at->timezone('Asia/Kolkata')->format('d M Y, h:i A').' IST',
                'telUrl' => 'tel:'.preg_replace('/[^\d+]/', '', $this->enquiry->phone),
                'whatsappUrl' => strlen($whatsappDigits) >= 10 ? 'https://wa.me/'.$whatsappDigits : null,
                'adminUrl' => route('admin.enquiries.index'),
            ],
        );
    }
}
