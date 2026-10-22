
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            // Contact details
            $table->string('phone_primary', 30)->nullable();
            $table->string('phone_secondary', 30)->nullable();
            $table->string('phone_tertiary', 30)->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('gst_number', 100)->nullable();
            $table->string('whatsapp_number', 30)->nullable();

            // Header and footer
            $table->text('footer_description')->nullable();
            $table->string('header_announcement')->nullable();
            $table->string('copyright_text')->nullable();

            // Social media links
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('linkedin_url')->nullable();

            // Google Maps
            $table->text('map_embed_url')->nullable();

            // Website colors
            $table->string('primary_color', 7)->nullable();
            $table->string('secondary_color', 7)->nullable();
            $table->string('header_bg_color', 7)->nullable();
            $table->string('header_text_color', 7)->nullable();
            $table->string('footer_bg_color', 7)->nullable();
            $table->string('footer_text_color', 7)->nullable();

            // SEO settings
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->text('seo_keywords')->nullable();
            $table->string('seo_og_image_path')->nullable();
            $table->string('default_meta_robots', 100)
                ->default('index, follow');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'phone_primary',
                'phone_secondary',
                'phone_tertiary',
                'email',
                'address',
                'gst_number',
                'whatsapp_number',
                'footer_description',
                'header_announcement',
                'copyright_text',
                'facebook_url',
                'instagram_url',
                'youtube_url',
                'linkedin_url',
                'map_embed_url',
                'primary_color',
                'secondary_color',
                'header_bg_color',
                'header_text_color',
                'footer_bg_color',
                'footer_text_color',
                'seo_title',
                'seo_description',
                'seo_keywords',
                'seo_og_image_path',
                'default_meta_robots',
            ]);
        });
    }
};
