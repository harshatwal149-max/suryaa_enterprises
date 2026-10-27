<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'subcategory_id',
        'name',
        'slug',
        'sku',
        'price',
        'discount_price',
        'show_price',
        'quantity',
        'size',
        'brand',
        'description',
        'meta_title',
        'meta_description',
        'image',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'discount_price' => 'decimal:2',
            'show_price' => 'boolean',
            'quantity' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Automatically generate a unique slug when creating a product.
     */
    protected static function booted(): void
    {
        static::creating(function (Product $product) {
            if (blank($product->slug)) {
                $product->slug = static::generateUniqueSlug(
                    $product->name
                );
            }
        });

        // Regenerate the slug if the product name changes
        // and a new slug has not been explicitly supplied.
        static::updating(function (Product $product) {
            if (
                $product->isDirty('name') &&
                ! $product->isDirty('slug')
            ) {
                $product->slug = static::generateUniqueSlug(
                    $product->name,
                    $product->id
                );
            }
        });
    }

    /**
     * Generate a unique slug, including for duplicate product names.
     */
    protected static function generateUniqueSlug(
        string $name,
        ?int $ignoreId = null
    ): string {
        $baseSlug = Str::slug($name);

        if ($baseSlug === '') {
            $baseSlug = 'product';
        }

        $slug = $baseSlug;
        $counter = 2;

        while (
            static::query()
            ->where('slug', $slug)
            ->when(
                $ignoreId !== null,
                fn($query) => $query->where('id', '!=', $ignoreId)
            )
            ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Whether the price may be shown on the website: the site-wide switch
     * and this product's own switch must both be on, and a price must be set.
     */
    public function priceVisible(): bool
    {
        return $this->show_price
            && SiteSetting::pricesVisible()
            && (float) $this->price > 0;
    }

    /**
     * Whether the website should show this product's discount.
     */
    public function hasVisibleDiscount(): bool
    {
        return $this->priceVisible()
            && $this->discount_price !== null
            && (float) $this->discount_price > 0
            && (float) $this->discount_price < (float) $this->price;
    }

    /**
     * Product category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Product subcategory.
     */
    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class);
    }

    /**
     * Additional product images.
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)
            ->orderBy('sort_order')
            ->orderBy('id');
    }
}
