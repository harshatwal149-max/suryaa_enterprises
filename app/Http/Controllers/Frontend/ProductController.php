<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductController extends Controller
{
    /** Display active products with optional AJAX-compatible search and filtering. */
    public function index(Request $request): View
    {
        $categories = Category::query()
            ->orderBy('name')
            ->get();

        $query = Product::with(['category', 'subcategory'])
            ->where('is_active', true);

        // Search product name, brand, SKU, and description.
        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));

            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by category when selected.
        if ($request->filled('category')) {
            $query->where('category_id', $request->input('category'));
        }

        $products = $query
            ->latest()
            ->paginate(12)
            ->withQueryString();

        // WhatsApp number is used by product cards.
        $settings = SiteSetting::first();
        $whatsappNumber = preg_replace(
            '/\D+/',
            '',
            (string) ($settings?->whatsapp_number ?? '')
        );

        return view('Products.index', compact(
            'products',
            'categories',
            'whatsappNumber'
        ));
    }

    /** Display a product detail page; missing/inactive products return home. */
    public function show(string|Product $slug): View|RedirectResponse
    {
        $product = $slug instanceof Product
            ? $slug
            : Product::where('slug', $slug)->first();

        if (!$product || !$product->is_active) {
            return redirect()->route('home');
        }

        $product->load(['category', 'subcategory', 'images']);

        $relatedProducts = Product::with('category')
            ->where('is_active', true)
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->latest()
            ->take(4)
            ->get();

        $settings = SiteSetting::first();
        $whatsappNumber = preg_replace(
            '/\D+/',
            '',
            (string) ($settings?->whatsapp_number ?? '')
        );

        return view('products.show', compact(
            'product',
            'relatedProducts',
            'whatsappNumber'
        ));
    }
}
