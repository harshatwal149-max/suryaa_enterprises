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
    /** Price the customer pays: the discount price when it is a real discount. */
    private const EFFECTIVE_PRICE = 'CASE WHEN discount_price > 0 AND discount_price < price THEN discount_price ELSE price END';

    /** Sort options shown on the listing page, keyed by query value. */
    public const SORT_OPTIONS = [
        'latest' => 'Latest',
        'price_asc' => 'Price: Low to High',
        'price_desc' => 'Price: High to Low',
        'discount' => 'Biggest Discount',
        'name' => 'Name: A to Z',
    ];

    /** Display active products with search, filters and sorting (AJAX-compatible). */
    public function index(Request $request): View
    {
        $activeProducts = fn ($query) => $query->where('is_active', true);

        $categories = Category::query()
            ->whereHas('products', $activeProducts)
            ->withCount(['products' => $activeProducts])
            ->orderBy('name')
            ->get();

        $query = Product::with('category')
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

        // Price range on the price the customer actually pays.
        if (is_numeric($request->input('min_price'))) {
            $query->whereRaw(self::EFFECTIVE_PRICE . ' >= ?', [(float) $request->input('min_price')]);
        }

        if (is_numeric($request->input('max_price'))) {
            $query->whereRaw(self::EFFECTIVE_PRICE . ' <= ?', [(float) $request->input('max_price')]);
        }

        if ($request->boolean('on_sale')) {
            $query->where('discount_price', '>', 0)
                ->whereColumn('discount_price', '<', 'price');
        }

        if ($request->boolean('in_stock')) {
            $query->where('quantity', '>', 0);
        }

        $sort = array_key_exists((string) $request->input('sort'), self::SORT_OPTIONS)
            ? (string) $request->input('sort')
            : 'latest';

        match ($sort) {
            'price_asc' => $query->orderByRaw(self::EFFECTIVE_PRICE . ' ASC'),
            'price_desc' => $query->orderByRaw(self::EFFECTIVE_PRICE . ' DESC'),
            'discount' => $query->orderByRaw('CASE WHEN discount_price > 0 AND discount_price < price THEN (price - discount_price) / price ELSE 0 END DESC'),
            'name' => $query->orderBy('name'),
            default => $query->latest(),
        };

        $products = $query
            ->paginate(12)
            ->withQueryString();

        $totalProducts = Product::where('is_active', true)->count();
        $sortOptions = self::SORT_OPTIONS;

        $whatsappNumber = SiteSetting::first()?->whatsappDigits() ?? '';

        return view('Products.index', compact(
            'products',
            'categories',
            'totalProducts',
            'sort',
            'sortOptions',
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

        $whatsappNumber = SiteSetting::first()?->whatsappDigits() ?? '';

        return view('Products.show', compact(
            'product',
            'relatedProducts',
            'whatsappNumber'
        ));
    }
}
