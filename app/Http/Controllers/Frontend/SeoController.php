<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    /** XML sitemap of every public page, category listing and active product. */
    public function sitemap(): Response
    {
        $latestProduct = Product::where('is_active', true)->max('updated_at');

        $pages = [
            ['loc' => route('home'), 'lastmod' => $latestProduct, 'changefreq' => 'weekly', 'priority' => '1.0'],
            ['loc' => route('products.index'), 'lastmod' => $latestProduct, 'changefreq' => 'weekly', 'priority' => '0.9'],
            ['loc' => route('about'), 'lastmod' => null, 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => route('contact'), 'lastmod' => null, 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => route('enquiry'), 'lastmod' => null, 'changefreq' => 'yearly', 'priority' => '0.5'],
        ];

        $categories = Category::query()
            ->whereHas('products', fn ($query) => $query->where('is_active', true))
            ->withMax(['products' => fn ($query) => $query->where('is_active', true)], 'updated_at')
            ->orderBy('name')
            ->get();

        foreach ($categories as $category) {
            $pages[] = [
                'loc' => route('products.index', ['category' => $category->id]),
                'lastmod' => $category->products_max_updated_at,
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ];
        }

        $products = Product::with(['images:id,product_id,image_path'])
            ->where('is_active', true)
            ->latest('updated_at')
            ->get(['id', 'name', 'slug', 'image', 'updated_at']);

        // The XML declaration is added here: "<?xml" inside a Blade file is read as a PHP open tag.
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n"
            . view('seo.sitemap', compact('pages', 'products'))->render();

        return response($xml, 200)
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    /** robots.txt pointing crawlers at the sitemap and away from private areas. */
    public function robots(): Response
    {
        $lines = [
            'User-agent: *',
            'Disallow: /admin',
            // Filtered listing URLs stay crawlable: they carry "noindex" and a canonical instead.
            '',
            'Sitemap: ' . route('sitemap'),
        ];

        return response(implode("\n", $lines) . "\n", 200)
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
