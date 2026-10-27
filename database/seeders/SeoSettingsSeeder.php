<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use App\Support\Seo;
use Illuminate\Database\Seeder;

class SeoSettingsSeeder extends Seeder
{
    /**
     * Fill the site-wide SEO settings with the recommended defaults.
     * Only empty fields are filled, so anything typed in the admin is kept.
     */
    public function run(): void
    {
        $settings = SiteSetting::firstOrCreate([]);

        $defaults = [
            'seo_title' => Seo::DEFAULT_TITLE,
            'seo_description' => Seo::DEFAULT_DESCRIPTION,
            'seo_keywords' => Seo::DEFAULT_KEYWORDS,
            'default_meta_robots' => 'index,follow',
        ];

        foreach ($defaults as $field => $value) {
            if (blank($settings->{$field})) {
                $settings->{$field} = $value;
            }
        }

        $settings->save();
    }
}
