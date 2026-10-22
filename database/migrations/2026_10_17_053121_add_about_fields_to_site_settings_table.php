<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('about_heading')->nullable();
            $table->text('about_description')->nullable();
            $table->string('about_highlight_one')->nullable();
            $table->string('about_highlight_two')->nullable();
            $table->string('about_highlight_three')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'about_heading',
                'about_description',
                'about_highlight_one',
                'about_highlight_two',
                'about_highlight_three',
            ]);
        });
    }
};
