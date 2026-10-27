<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\SiteSetting;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::with(['category', 'subcategory'])
            ->latest()
            ->paginate(15);

        $categories = Category::orderBy('name')->get();

        $pricesVisible = SiteSetting::pricesVisible();

        return view('admin.products.index', compact('products', 'categories', 'pricesVisible'));
    }

    public function create(): View
    {
        $categories = Category::where('is_active', true)
            ->orderBy('name')
            ->get();

        $subcategories = Subcategory::orderBy('name')->get();

        return view('admin.products.create', compact(
            'categories',
            'subcategories'
        ));
    }

    public function store(Request $request): Response
    {
        $validated = $this->validateProduct($request);

        $galleryFiles = $request->file('gallery_images', []);

        if (count($galleryFiles) > 5) {
            return back()
                ->withErrors([
                    'gallery_images' => 'You can upload a maximum of 5 gallery images.',
                ])
                ->withInput();
        }

        $storedFiles = [];

        try {
            $product = DB::transaction(function () use (
                $request,
                $validated,
                $galleryFiles,
                &$storedFiles
            ) {
                $validated['slug'] = $this->uniqueSlug(
                    $validated['name']
                );

                $validated['image'] = $request->file('image')
                    ->store('products', 'public');

                $storedFiles[] = $validated['image'];

                unset($validated['gallery_images']);

                $validated['is_active'] = $request->boolean('is_active');
                $validated['show_price'] = $request->boolean('show_price');

                $product = Product::create($validated);

                foreach ($galleryFiles as $index => $file) {
                    $path = $file->store('products/gallery', 'public');

                    $storedFiles[] = $path;

                    $product->images()->create([
                        'image_path' => $path,
                        'sort_order' => $index,
                    ]);
                }

                return $product;
            });
        } catch (Throwable $exception) {
            Storage::disk('public')->delete($storedFiles);

            report($exception);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product could not be saved. Please try again.'
                ], 500);
            }

            return back()
                ->withInput()
                ->with('error', 'Product could not be saved. Please try again.');
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product created successfully.'
            ]);
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show(Product $product): Response
    {
        return redirect()->route('admin.products.edit', $product);
    }

    public function edit(Product $product): View
    {
        $product->load('images');

        $categories = Category::orderBy('name')->get();
        $subcategories = Subcategory::orderBy('name')->get();

        return view('admin.products.edit', compact(
            'product',
            'categories',
            'subcategories'
        ));
    }

    public function update(
        Request $request,
        Product $product
    ): Response {
        $validated = $this->validateProduct($request, $product);

        $galleryFiles = $request->file('gallery_images', []);
        $existingGalleryCount = $product->images()->count();

        if ($existingGalleryCount + count($galleryFiles) > 5) {
            return back()
                ->withErrors([
                    'gallery_images' =>
                    'A product can have a maximum of 5 gallery images. Delete an existing image before adding another.',
                ])
                ->withInput();
        }

        $storedFiles = [];
        $oldMainImage = $product->image;

        try {
            DB::transaction(function () use (
                $request,
                $product,
                $validated,
                $galleryFiles,
                &$storedFiles
            ) {
                if ($validated['name'] !== $product->name) {
                    $validated['slug'] = $this->uniqueSlug(
                        $validated['name'],
                        $product->id
                    );
                }

                if ($request->hasFile('image')) {
                    $validated['image'] = $request->file('image')
                        ->store('products', 'public');

                    $storedFiles[] = $validated['image'];
                }

                unset($validated['gallery_images']);

                $validated['is_active'] = $request->boolean('is_active');
                $validated['show_price'] = $request->boolean('show_price');

                $product->update($validated);

                $nextSortOrder = (int) $product->images()->max('sort_order') + 1;

                foreach ($galleryFiles as $file) {
                    $path = $file->store('products/gallery', 'public');

                    $storedFiles[] = $path;

                    $product->images()->create([
                        'image_path' => $path,
                        'sort_order' => $nextSortOrder++,
                    ]);
                }
            });
        } catch (Throwable $exception) {
            Storage::disk('public')->delete($storedFiles);

            report($exception);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product could not be updated. Please try again.'
                ], 500);
            }

            return back()
                ->withInput()
                ->with('error', 'Product could not be updated. Please try again.');
        }

        if ($request->hasFile('image') && $oldMainImage) {
            Storage::disk('public')->delete($oldMainImage);
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully.'
            ]);
        }

        return redirect()
            ->route('admin.products.edit', $product)
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Toggle product active status.
     */
    public function toggleStatus(Request $request, Product $product): Response
    {
        $product->is_active = ! $product->is_active;
        $product->save();

        $status = $product->is_active ? 'Active' : 'Inactive';
        $message = "Product status successfully {$status} kar diya gaya!";

        if ($request->ajax()) {
            return response()->json([
                'success'   => true,
                'is_active' => $product->is_active,
                'status'    => $status,
                'message'   => $message,
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Delete one gallery image from a product.
     */
    public function destroyImage(
        Request $request,
        Product $product,
        ProductImage $image
    ): Response {
        abort_unless(
            (int) $image->product_id === (int) $product->id,
            404
        );

        $path = $image->image_path;

        DB::transaction(function () use ($image) {
            $image->delete();
        });

        Storage::disk('public')->delete($path);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Gallery image deleted successfully.'
            ]);
        }

        return redirect()
            ->route('admin.products.edit', $product)
            ->with('success', 'Gallery image deleted successfully.');
    }

    public function destroy(Request $request, Product $product): Response
    {
        $galleryPaths = $product->images()
            ->pluck('image_path')
            ->all();

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        if (!empty($galleryPaths)) {
            Storage::disk('public')->delete($galleryPaths);
        }

        $product->delete();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully.'
            ]);
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    /**
     * Turn product prices on or off for the whole website.
     */
    public function togglePriceVisibility(Request $request): Response
    {
        $settings = SiteSetting::firstOrCreate([]);
        $settings->show_product_prices = ! $settings->show_product_prices;
        $settings->save();

        $message = $settings->show_product_prices
            ? 'Product prices are now visible on the website.'
            : 'Product prices are now hidden on the whole website.';

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'visible' => $settings->show_product_prices,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Apply one action to several selected products at once.
     */
    public function bulk(Request $request): Response
    {
        $validated = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', Rule::exists('products', 'id')],
            'action' => ['required', Rule::in([
                'delete',
                'activate',
                'deactivate',
                'show_price',
                'hide_price',
                'category',
            ])],
            'category_id' => [
                'required_if:action,category',
                'nullable',
                'integer',
                Rule::exists('categories', 'id'),
            ],
        ]);

        $products = Product::whereIn('id', $validated['ids']);
        $count = count($validated['ids']);

        if ($validated['action'] === 'delete') {
            $paths = Product::whereIn('id', $validated['ids'])
                ->whereNotNull('image')
                ->pluck('image')
                ->merge(
                    ProductImage::whereIn('product_id', $validated['ids'])
                        ->pluck('image_path')
                )
                ->filter()
                ->all();

            $products->delete();

            Storage::disk('public')->delete($paths);

            $message = "{$count} product(s) deleted successfully.";
        } else {
            $products->update(match ($validated['action']) {
                'activate' => ['is_active' => true],
                'deactivate' => ['is_active' => false],
                'show_price' => ['show_price' => true],
                'hide_price' => ['show_price' => false],
                // The old subcategory belongs to the previous category.
                'category' => [
                    'category_id' => $validated['category_id'],
                    'subcategory_id' => null,
                ],
            });

            $message = "{$count} product(s) updated successfully.";
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }

    private function validateProduct(
        Request $request,
        ?Product $product = null
    ): array {
        return $request->validate([
            'category_id' => [
                'required',
                'integer',
                Rule::exists('categories', 'id'),
            ],

            'subcategory_id' => [
                'nullable',
                'integer',
                Rule::exists('subcategories', 'id'),
            ],

            'name' => ['required', 'string', 'max:255'],

            'sku' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('products', 'sku')->ignore($product?->id),
            ],

            'price' => ['required', 'numeric', 'min:0'],

            'discount_price' => [
                'nullable',
                'numeric',
                'min:0',
                'lte:price',
            ],

            'quantity' => ['required', 'integer', 'min:0'],

            'size' => ['nullable', 'string', 'max:255'],

            'brand' => ['nullable', 'string', 'max:255'],

            'description' => ['nullable', 'string'],

            'meta_title' => ['nullable', 'string', 'max:255'],

            'meta_description' => ['nullable', 'string', 'max:320'],

            'image' => [
                $product ? 'nullable' : 'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'gallery_images' => ['nullable', 'array', 'max:5'],

            'gallery_images.*' => [
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'is_active' => ['nullable', 'boolean'],

            'show_price' => ['nullable', 'boolean'],
        ]);
    }

    private function uniqueSlug(
        string $name,
        ?int $ignoreId = null
    ): string {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug ?: 'product';
        $counter = 1;

        while (
            Product::where('slug', $slug)
            ->when(
                $ignoreId,
                fn($query) => $query->where('id', '!=', $ignoreId)
            )
            ->exists()
        ) {
            $slug = ($baseSlug ?: 'product') . '-' . $counter++;
        }

        return $slug;
    }
}
