<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Category relationships
            $table->foreignId('category_id')
                ->constrained('categories')
                ->cascadeOnDelete();

            $table->foreignId('subcategory_id')
                ->nullable()
                ->constrained('subcategories')
                ->nullOnDelete();

            // Basic product information
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->nullable()->unique();

            // Pricing and inventory
            $table->decimal('price', 12, 2);
            $table->decimal('discount_price', 12, 2)->nullable();
            $table->unsignedInteger('quantity')->default(0);
            $table->string('size')->nullable();

            // Brand and description
            $table->string('brand')->nullable();
            $table->text('description')->nullable();

            // Images
            $table->string('image')->nullable();

            // Product status
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index(['category_id', 'subcategory_id']);
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
