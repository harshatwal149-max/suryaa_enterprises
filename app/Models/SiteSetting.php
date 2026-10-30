<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        // Website identity
        'site_name',
        'logo_path',
        'favicon_path',

        // About Us
        'about_image',
        'about_heading',
        'about_description',
        'about_highlight_one',
        'about_highlight_two',
        'about_highlight_three',
        'about_highlight_four',

        // Contact details
        'phone_primary',
        'phone_secondary',
        'phone_tertiary',
        'email',
        'address',
        'gst_number',
        'whatsapp_number',
        'map_embed_url',

        // Header and footer
        'header_announcement',
        'footer_description',
        'copyright_text',

        // Social media
        'facebook_url',
        'instagram_url',
        'youtube_url',
        'linkedin_url',

        // Theme colors
        'primary_color',
        'secondary_color',
        'header_bg_color',
        'header_text_color',
        'footer_bg_color',
        'footer_text_color',

        // SEO
        'seo_title',
        'seo_description',
        'seo_keywords',
        'default_meta_robots',
        'seo_og_image_path',

        // Products
        'show_product_prices',
    ];

    protected function casts(): array
    {
        return [
            'show_product_prices' => 'boolean',
        ];
    }

    /**
     * Whether product prices are shown on the website (site-wide switch).
     * Memoised per request because every product card asks.
     */
    public static function pricesVisible(): bool
    {
        return once(fn () => (bool) (static::first()?->show_product_prices ?? true));
    }

    /**
     * WhatsApp number as digits with country code, ready for wa.me links.
     * Numbers are stored as 10-digit Indian mobiles, so 91 is prefixed.
     */
    public function whatsappDigits(): string
    {
        return static::toWhatsappDigits((string) $this->whatsapp_number);
    }

    /**
     * Strips a phone number to digits and prefixes 91 to 10-digit Indian mobiles.
     */
    public static function toWhatsappDigits(string $number): string
    {
        $digits = preg_replace('/\D+/', '', $number);

        return strlen($digits) === 10 ? '91'.$digits : $digits;
    }
}
