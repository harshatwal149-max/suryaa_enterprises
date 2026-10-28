<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('products', 'show_price')) {
            Schema::table('products', function (Blueprint $table) {
                $table->boolean('show_price')->default(true);
            });
        }
    }

    public function down(): void
    {
        // This column is also managed by the original migration.
        // Do not drop it from this duplicate migration.
    }
};
