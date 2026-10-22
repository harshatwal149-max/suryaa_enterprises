<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SiteSettingController extends Controller
{
    /**
     * Get existing settings without creating duplicate records.
     */
    private function getSettings(): SiteSetting
    {
        return SiteSetting::first() ?? new SiteSetting();
    }

    /**
     * Display general website settings.
     */
    public function edit()
    {
        $settings = SiteSetting::first();

        return view('admin.settings.edit', compact('settings'));
    }

    /**
     * Update general website settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => ['required', 'string', 'max:255'],

            // About Us
            'about_heading' => ['nullable', 'string', 'max:255'],
            'about_description' => ['nullable', 'string', 'max:10000'],
            'about_highlight_one' => ['nullable', 'string', 'max:255'],
            'about_highlight_two' => ['nullable', 'string', 'max:255'],
            'about_highlight_three' => ['nullable', 'string', 'max:255'],
            'about_highlight_four' => ['nullable', 'string', 'max:255'],

            // Contact details
            'phone_primary' => ['nullable', 'string', 'max:30'],
            'phone_secondary' => ['nullable', 'string', 'max:30'],
            'phone_tertiary' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:5000'],
            'gst_number' => ['nullable', 'string', 'max:100'],
            'whatsapp_number' => ['nullable', 'string', 'max:30'],

            // Header and footer
            'footer_description' => ['nullable', 'string', 'max:5000'],
            'copyright_text' => ['nullable', 'string', 'max:255'],

            // Social media
            'facebook_url' => ['nullable', 'url', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'youtube_url' => ['nullable', 'url', 'max:255'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],

            // Google Maps
            'map_embed_url' => ['nullable', 'url', 'max:2000'],

            // SEO
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:5000'],
            'seo_keywords' => ['nullable', 'string', 'max:5000'],
            'default_meta_robots' => [
                'nullable',
                Rule::in([
                    'index,follow',
                    'noindex,nofollow',
                    'noindex,follow',
                    'index,nofollow',
                ]),
            ],

            // Uploads
            'about_image' => [
                'nullable',
                'image',
                'mimes:png,jpg,jpeg,webp',
                'max:4096',
            ],
            'logo' => [
                'nullable',
                'image',
                'mimes:png,jpg,jpeg,webp,svg',
                'max:2048',
            ],
            'favicon' => [
                'nullable',
                'mimes:png,ico,jpg,jpeg,webp',
                'max:1024',
            ],
            'seo_og_image' => [
                'nullable',
                'image',
                'mimes:png,jpg,jpeg,webp',
                'max:2048',
            ],
        ]);

        $settings = $this->getSettings();

        $fields = [
            'site_name',
            'about_heading',
            'about_description',
            'about_highlight_one',
            'about_highlight_two',
            'about_highlight_three',
            'about_highlight_four',
            'phone_primary',
            'phone_secondary',
            'phone_tertiary',
            'email',
            'address',
            'gst_number',
            'whatsapp_number',
            'footer_description',
            'copyright_text',
            'facebook_url',
            'instagram_url',
            'youtube_url',
            'linkedin_url',
            'map_embed_url',
            'seo_title',
            'seo_description',
            'seo_keywords',
            'default_meta_robots',
        ];

        foreach ($fields as $field) {
            if (!array_key_exists($field, $validated)) {
                continue;
            }

            if ($field === 'default_meta_robots') {
                $settings->{$field} = $validated[$field] ?? 'index,follow';
                continue;
            }

            $settings->{$field} = $validated[$field];
        }

        $this->storeUploadedFile($request, $settings, 'about_image', 'about_image');
        $this->storeUploadedFile($request, $settings, 'logo', 'logo_path');
        $this->storeUploadedFile($request, $settings, 'favicon', 'favicon_path');
        $this->storeUploadedFile($request, $settings, 'seo_og_image', 'seo_og_image_path');

        $settings->save();

        return redirect()
            ->route('admin.settings.edit')
            ->with('success', 'Website settings updated successfully.');
    }

    /**
     * Compatibility method for routes that still point to themeColors.
     */
    public function themeColors()
    {
        return $this->editThemeColors();
    }

    /**
     * Display Theme Colors page.
     */
    public function editThemeColors()
    {
        $settings = SiteSetting::first();

        return view('admin.settings.theme-colors', compact('settings'));
    }

    /**
     * Update only theme colors.
     */
    public function updateThemeColors(Request $request)
    {
        $validated = $request->validate([
            'primary_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'secondary_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'header_bg_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'header_text_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'footer_bg_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'footer_text_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ]);

        $settings = $this->getSettings();

        foreach ($validated as $field => $value) {
            $settings->{$field} = $value;
        }

        $settings->save();

        return redirect()
            ->route('admin.theme-colors.edit')
            ->with('success', 'Theme colors updated successfully.');
    }

    /**
     * Display Logo & Favicon page.
     */
    public function editLogo()
    {
        $settings = SiteSetting::first();

        return view('admin.settings.logo-favicon', compact('settings'));
    }

    /**
     * Update only Logo & Favicon.
     */
    public function updateLogo(Request $request)
    {
        $request->validate([
            'logo' => [
                'nullable',
                'image',
                'mimes:png,jpg,jpeg,webp,svg',
                'max:2048',
            ],
            'favicon' => [
                'nullable',
                'mimes:png,ico,jpg,jpeg,webp',
                'max:1024',
            ],
        ]);

        $settings = $this->getSettings();

        $this->storeUploadedFile($request, $settings, 'logo', 'logo_path');
        $this->storeUploadedFile($request, $settings, 'favicon', 'favicon_path');

        $settings->save();

        return redirect()
            ->route('admin.settings.logo')
            ->with('success', 'Logo and favicon updated successfully.');
    }

    /**
     * Display Contact Details page.
     */
    public function editContact()
    {
        $settings = SiteSetting::first();

        return view('admin.settings.contact-details', compact('settings'));
    }

    /**
     * Update only Contact Details.
     */
    public function updateContact(Request $request)
    {
        $validated = $request->validate([
            'phone_primary' => ['nullable', 'string', 'max:30'],
            'phone_secondary' => ['nullable', 'string', 'max:30'],
            'phone_tertiary' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:5000'],
            'gst_number' => ['nullable', 'string', 'max:100'],
            'whatsapp_number' => ['nullable', 'string', 'max:30'],
            'map_embed_url' => ['nullable', 'url', 'max:2000'],
        ]);

        $settings = $this->getSettings();

        foreach ($validated as $field => $value) {
            $settings->{$field} = $value;
        }

        $settings->save();

        return redirect()
            ->route('admin.settings.contact')
            ->with('success', 'Contact details updated successfully.');
    }

    /**
     * Display SEO Settings page.
     */
    public function editSeo()
    {
        $settings = SiteSetting::first();

        return view('admin.settings.seo', compact('settings'));
    }

    /**
     * Update only SEO Settings.
     */
    public function updateSeo(Request $request)
    {
        $validated = $request->validate([
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:5000'],
            'seo_keywords' => ['nullable', 'string', 'max:5000'],
            'default_meta_robots' => [
                'nullable',
                Rule::in([
                    'index,follow',
                    'noindex,nofollow',
                    'noindex,follow',
                    'index,nofollow',
                ]),
            ],
            'seo_og_image' => [
                'nullable',
                'image',
                'mimes:png,jpg,jpeg,webp',
                'max:2048',
            ],
        ]);

        $settings = $this->getSettings();

        foreach (['seo_title', 'seo_description', 'seo_keywords'] as $field) {
            $settings->{$field} = $validated[$field] ?? null;
        }

        $settings->default_meta_robots = $validated['default_meta_robots'] ?? 'index,follow';

        $this->storeUploadedFile($request, $settings, 'seo_og_image', 'seo_og_image_path');

        $settings->save();

        return redirect()
            ->route('admin.settings.seo')
            ->with('success', 'SEO settings updated successfully.');
    }

    /**
     * Display Social Media page.
     */
    public function editSocial()
    {
        $settings = SiteSetting::first();

        return view('admin.settings.social-media', compact('settings'));
    }

    /**
     * Update only Social Media links.
     */
    public function updateSocial(Request $request)
    {
        $validated = $request->validate([
            'facebook_url' => ['nullable', 'url', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'youtube_url' => ['nullable', 'url', 'max:255'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
        ]);

        $settings = $this->getSettings();

        foreach ($validated as $field => $value) {
            $settings->{$field} = $value;
        }

        $settings->save();

        return redirect()
            ->route('admin.settings.social')
            ->with('success', 'Social media links updated successfully.');
    }

    /**
     * Store an uploaded file and replace the old file after successful storage.
     */
    private function storeUploadedFile(
        Request $request,
        SiteSetting $settings,
        string $inputName,
        string $columnName
    ): void {
        if (!$request->hasFile($inputName)) {
            return;
        }

        $newPath = $request->file($inputName)->store('site-settings', 'public');

        if (!$newPath) {
            return;
        }

        $oldPath = $settings->{$columnName};
        $settings->{$columnName} = $newPath;

        if (
            $oldPath &&
            $oldPath !== $newPath &&
            Storage::disk('public')->exists($oldPath)
        ) {
            Storage::disk('public')->delete($oldPath);
        }
    }
}
