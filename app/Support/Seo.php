<?php

namespace App\Support;

use App\Models\Category;
use App\Models\Product;
use App\Models\SiteSetting;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

/**
 * Builds meta text and schema.org JSON-LD for the public website.
 */
class Seo
{
    public const DEFAULT_SITE_NAME = 'Suryaa Enterprises';

    /** Where the business sells and installs; used in titles, descriptions and schema. */
    public const AREA = 'Kangra, Himachal Pradesh';
    public const AREA_SHORT = 'Kangra, HP';
    public const REGION = 'Himachal Pradesh';

    public const DEFAULT_TITLE = 'Suryaa Enterprises | Solar Dealer in Kangra, Himachal Pradesh';

    public const DEFAULT_DESCRIPTION = 'ISO 9001:2015 certified Super Solar distributor in Kangra, HP. Solar water heaters, rooftop solar plants, street lights & fencing with expert installation.';

    public const DEFAULT_KEYWORDS = 'solar dealer in Kangra, solar company in Himachal Pradesh, solar water heater Kangra, '
        . 'rooftop solar system Himachal Pradesh, on-grid solar plant, PM Surya Ghar subsidy, solar street light, '
        . 'solar fencing for farms, solar home lighting system, solar inverter, solar panel price, '
        . 'Super Solar distributor, Suryaa Enterprises, solar installation Dharamshala, solar Palampur';

    /** Search copy for each product category, keyed by category slug. */
    public const CATEGORY_COPY = [
        'solar-water-heater' => [
            'title' => 'Solar Water Heaters in Kangra, HP',
            'description' => 'Glass line, ETC & FPC solar water heaters (100–500 LPD) for homes, hotels and hostels in Kangra, HP. Built for hill winters, with installation & service.',
        ],
        'solar-street-lights' => [
            'title' => 'Solar Street Lights in Himachal Pradesh',
            'description' => 'All-in-one & semi-integrated LED solar street lights with lithium batteries and motion sensors for roads, villages and campuses across Himachal Pradesh.',
        ],
        'solar-power-plants' => [
            'title' => 'Rooftop Solar Systems in Kangra, HP',
            'description' => 'On-grid, off-grid & hybrid rooftop solar systems (1–5 kW+) in Kangra, HP. Net metering, PM Surya Ghar subsidy help, mono PERC panels and installation.',
        ],
        'solar-fencing' => [
            'title' => 'Solar Fencing for Farms in Himachal Pradesh',
            'description' => 'Solar fence energizers & complete fencing kits to protect crops and orchards from monkeys and wild animals. Supplied and installed across Himachal Pradesh.',
        ],
        'solar-home-lighting' => [
            'title' => 'Solar Home Lighting & Inverters in Kangra',
            'description' => 'Solar lanterns, home lighting kits, garden lights and solar inverters (PCU) for reliable backup during power cuts. Genuine products from Kangra, HP.',
        ],
    ];

    public static function settings(): ?SiteSetting
    {
        return once(fn () => SiteSetting::first());
    }

    public static function siteName(): string
    {
        return static::settings()?->site_name ?: self::DEFAULT_SITE_NAME;
    }

    /**
     * Plain text cut to a search-snippet length (about 160 characters).
     */
    public static function excerpt(?string $text, int $limit = 160): string
    {
        $plain = trim(preg_replace('/\s+/u', ' ', strip_tags((string) $text)));

        return Str::limit($plain, $limit, '…');
    }

    public static function storageUrl(?string $path): ?string
    {
        return filled($path) ? asset('storage/' . ltrim($path, '/')) : null;
    }

    public static function logoUrl(): string
    {
        return static::storageUrl(static::settings()?->logo_path)
            ?? asset('images/logo-mark.svg');
    }

    public static function defaultImageUrl(): string
    {
        return static::storageUrl(static::settings()?->seo_og_image_path)
            ?? static::logoUrl();
    }

    /**
     * Social profile URLs for the Organization "sameAs" list.
     */
    public static function socialProfiles(): array
    {
        $settings = static::settings();

        return collect(['facebook_url', 'instagram_url', 'youtube_url', 'linkedin_url', 'twitter_url'])
            ->map(fn ($field) => $settings?->{$field})
            ->filter(fn ($url) => filled($url) && Str::startsWith($url, ['http://', 'https://']))
            ->values()
            ->all();
    }

    /**
     * Page title for a category listing.
     */
    public static function categoryTitle(Category $category): string
    {
        return self::CATEGORY_COPY[$category->slug]['title']
            ?? Str::title($category->name) . ' in ' . self::AREA_SHORT;
    }

    /**
     * Meta description for a category listing.
     */
    public static function categoryDescription(Category $category): string
    {
        if (isset(self::CATEGORY_COPY[$category->slug])) {
            return self::CATEGORY_COPY[$category->slug]['description'];
        }

        $intro = filled($category->description)
            ? rtrim(static::excerpt($category->description, 100), '.') . '.'
            : 'Quality ' . Str::lower($category->name) . '.';

        return static::excerpt('Buy ' . Str::title($category->name) . ' in ' . self::AREA_SHORT . '. ' . $intro . ' Genuine products with installation & support from ' . static::siteName() . '.');
    }

    /**
     * Search title for a product: the admin's SEO title, else name plus location when it fits.
     */
    public static function productTitle(Product $product): string
    {
        if (filled($product->meta_title)) {
            return $product->meta_title;
        }

        $withArea = $product->name . ' in ' . self::AREA_SHORT;

        return Str::length($withArea) <= 60 ? $withArea : $product->name;
    }

    /**
     * Search snippet for a product: the admin's SEO description, else built from the product data.
     */
    public static function productDescription(Product $product): string
    {
        if (filled($product->meta_description)) {
            return static::excerpt($product->meta_description, 320);
        }

        $lead = 'Buy ' . $product->name
            . (filled($product->size) && ! Str::contains($product->name, $product->size) ? ' (' . $product->size . ')' : '')
            . (filled($product->brand) && ! Str::contains($product->name, $product->brand) ? ' by ' . $product->brand : '')
            . ' in ' . self::AREA_SHORT . '.';

        // First sentence of the product description adds the specific selling point.
        $detail = Str::before(static::excerpt($product->description, 400), '. ');
        $detail = $detail !== '' ? rtrim($detail, '.…') . '.' : '';

        // Longest closing line that still fits the 160 character snippet.
        $endings = [' Installation & support by ' . static::siteName() . '.', ' Expert installation & support.', ' Expert installation.', ''];

        foreach ($endings as $ending) {
            $text = trim($lead . ' ' . $detail . $ending);

            if (Str::length($text) <= 160) {
                break;
            }
        }

        return static::excerpt($text);
    }

    /**
     * The business as a LocalBusiness, referenced by @id from every other block.
     */
    public static function organization(): array
    {
        $settings = static::settings();

        $phones = collect([
            $settings?->phone_primary,
            $settings?->phone_secondary,
            $settings?->phone_tertiary,
        ])->filter()->values();

        return array_filter([
            '@type' => 'LocalBusiness',
            '@id' => url('/') . '#organization',
            'name' => static::siteName(),
            'url' => url('/'),
            'logo' => static::logoUrl(),
            'image' => static::defaultImageUrl(),
            'description' => static::excerpt($settings?->seo_description ?: $settings?->footer_description ?: self::DEFAULT_DESCRIPTION, 300),
            'slogan' => 'ISO 9001:2015 Certified · Authorised Super Solar Distributor, H.P.',
            'telephone' => $phones->first(),
            'email' => $settings?->email,
            'address' => array_filter([
                '@type' => 'PostalAddress',
                'streetAddress' => filled($settings?->address) ? static::excerpt($settings->address, 300) : null,
                'addressLocality' => 'Kangra',
                'addressRegion' => self::REGION,
                'addressCountry' => 'IN',
            ]),
            'contactPoint' => $phones->map(fn ($phone) => [
                '@type' => 'ContactPoint',
                'telephone' => $phone,
                'contactType' => 'sales',
                'areaServed' => 'IN',
                'availableLanguage' => ['English', 'Hindi'],
            ])->all() ?: null,
            'areaServed' => [
                ['@type' => 'AdministrativeArea', 'name' => 'Kangra'],
                ['@type' => 'State', 'name' => self::REGION],
            ],
            'knowsAbout' => [
                'Solar water heaters',
                'Rooftop solar power plants',
                'Solar street lights',
                'Solar fencing',
                'Solar home lighting systems',
                'PM Surya Ghar Muft Bijli Yojana subsidy',
                'Net metering',
            ],
            'taxID' => $settings?->gst_number,
            'sameAs' => static::socialProfiles() ?: null,
        ]);
    }

    /**
     * The website, with a sitelinks search box that searches products.
     */
    public static function website(): array
    {
        return [
            '@type' => 'WebSite',
            '@id' => url('/') . '#website',
            'url' => url('/'),
            'name' => static::siteName(),
            'inLanguage' => 'en-IN',
            'publisher' => ['@id' => url('/') . '#organization'],
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => [
                    '@type' => 'EntryPoint',
                    'urlTemplate' => route('products.index') . '?search={search_term_string}',
                ],
                'query-input' => 'required name=search_term_string',
            ],
        ];
    }

    /**
     * A web page of the given schema.org type (WebPage, AboutPage, ContactPage, CollectionPage…).
     */
    public static function webPage(string $type, string $name, ?string $description = null, array $extra = []): array
    {
        return array_filter([
            '@type' => $type,
            '@id' => url()->current() . '#webpage',
            'url' => url()->current(),
            'name' => $name,
            'description' => $description,
            'inLanguage' => 'en-IN',
            'isPartOf' => ['@id' => url('/') . '#website'],
            'about' => ['@id' => url('/') . '#organization'],
        ]) + $extra;
    }

    /**
     * @param  array<string, string>  $items  label => absolute URL, in order from Home.
     */
    public static function breadcrumbs(array $items): array
    {
        $position = 0;

        return [
            '@type' => 'BreadcrumbList',
            'itemListElement' => collect($items)->map(fn ($url, $label) => [
                '@type' => 'ListItem',
                'position' => ++$position,
                'name' => $label,
                'item' => $url,
            ])->values()->all(),
        ];
    }

    public static function product(Product $product): array
    {
        $images = collect([$product->image])
            ->merge($product->images->pluck('image_path'))
            ->filter()
            ->unique()
            ->map(fn ($path) => static::storageUrl($path))
            ->values()
            ->all();

        $url = route('products.show', ['slug' => $product->slug]);

        $schema = array_filter([
            '@type' => 'Product',
            '@id' => $url . '#product',
            'name' => $product->name,
            'url' => $url,
            'image' => $images ?: [static::defaultImageUrl()],
            'description' => static::excerpt($product->description, 5000) ?: $product->name,
            'sku' => $product->sku,
            'category' => $product->category?->name,
            'brand' => filled($product->brand) ? ['@type' => 'Brand', 'name' => $product->brand] : null,
            'additionalProperty' => filled($product->size) ? [[
                '@type' => 'PropertyValue',
                'name' => 'Capacity / Size',
                'value' => $product->size,
            ]] : null,
        ]);

        // Prices are only published in structured data when the website shows them.
        if ($product->priceVisible()) {
            $schema['offers'] = [
                '@type' => 'Offer',
                'url' => $url,
                'priceCurrency' => 'INR',
                'price' => number_format(
                    (float) ($product->hasVisibleDiscount() ? $product->discount_price : $product->price),
                    2,
                    '.',
                    ''
                ),
                'availability' => (int) $product->quantity > 0
                    ? 'https://schema.org/InStock'
                    : 'https://schema.org/OutOfStock',
                'itemCondition' => 'https://schema.org/NewCondition',
                'seller' => ['@id' => url('/') . '#organization'],
            ];
        }

        return $schema;
    }

    /**
     * Products shown on a listing page, as an ItemList.
     */
    public static function itemList(iterable $products, int $offset = 0): array
    {
        // collect() on a paginator would give its array form (current_page, data…), not the products.
        if ($products instanceof \Illuminate\Contracts\Pagination\Paginator) {
            $products = $products->items();
        }

        $position = $offset;

        return [
            '@type' => 'ItemList',
            'itemListElement' => collect($products)->map(fn (Product $product) => [
                '@type' => 'ListItem',
                'position' => ++$position,
                'url' => route('products.show', ['slug' => $product->slug]),
                'name' => $product->name,
            ])->values()->all(),
        ];
    }

    /**
     * Render one or more schema blocks as a single JSON-LD <script> using @graph.
     */
    public static function jsonLd(array ...$blocks): HtmlString
    {
        $json = json_encode(
            ['@context' => 'https://schema.org', '@graph' => array_values(array_filter($blocks))],
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_PRETTY_PRINT
        );

        return new HtmlString('<script type="application/ld+json">' . $json . '</script>');
    }
}
