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
    ];
}
