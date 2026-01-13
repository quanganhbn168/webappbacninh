<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ad_banners', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Human-readable name
            $table->string('slot')->index(); // Slot identifier (after_hero, before_blog, sidebar)
            $table->string('image')->nullable(); // Path to image
            $table->string('link')->nullable(); // URL when clicked
            $table->string('alt_text')->nullable(); // Alt text for SEO
            $table->boolean('is_active')->default(true);
            $table->boolean('open_new_tab')->default(true);
            $table->integer('order')->default(0);
            $table->timestamp('starts_at')->nullable(); // Schedule start
            $table->timestamp('ends_at')->nullable(); // Schedule end
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_banners');
    }
};
