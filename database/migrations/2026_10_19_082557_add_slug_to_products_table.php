<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        // Add slug only if it does not already exist.
        if (! Schema::hasColumn('products', 'slug')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('slug')->nullable()->after('name');
            });
        }

        // Generate slugs for existing products.
        DB::table('products')
            ->select('id', 'name', 'slug')
            ->orderBy('id')
            ->chunkById(200, function ($products) {
                foreach ($products as $product) {
                    if (! blank($product->slug)) {
                        continue;
                    }

                    $baseSlug = Str::slug($product->name ?? '');

                    if ($baseSlug === '') {
                        $baseSlug = 'product-'.$product->id;
                    }

                    $slug = $baseSlug;
                    $counter = 2;

                    while (
                        DB::table('products')
                            ->where('slug', $slug)
                            ->exists()
                    ) {
                        $slug = $baseSlug.'-'.$counter;
                        $counter++;
                    }

                    DB::table('products')
                        ->where('id', $product->id)
                        ->update(['slug' => $slug]);
                }
            });

        // Add unique index only if one does not already exist.
        $indexExists = Schema::hasIndex('products', 'products_slug_unique');

        if (! $indexExists) {
            Schema::table('products', function (Blueprint $table) {
                $table->unique('slug', 'products_slug_unique');
            });
        }
    }

    public function down(): void
    {
        // Keep the existing slug column and data intact.
        // The column may have existed before this migration.
        if (Schema::hasColumn('products', 'slug')) {
            $indexExists = Schema::hasIndex('products', 'products_slug_unique');

            if ($indexExists) {
                Schema::table('products', function (Blueprint $table) {
                    $table->dropUnique('products_slug_unique');
                });
            }
        }
    }
};
