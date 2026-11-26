<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Articles, fixed pages, menus and the other content edited in the admin. */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('post_categories', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('color')->default('#007bff');
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->foreignId('image_id')->nullable()->constrained('curator')->nullOnDelete();
            $table->foreignId('og_media_id')->nullable()->constrained('curator')->nullOnDelete();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
        });

        Schema::create('posts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('post_categories')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('summary')->nullable();
            $table->longText('content');
            $table->json('faqs')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->json('data')->nullable();
            $table->timestamps();
            $table->foreignId('featured_media_id')->nullable()->constrained('curator')->nullOnDelete();
            $table->foreignId('og_media_id')->nullable()->constrained('curator')->nullOnDelete();
        });

        Schema::create('pages', function (Blueprint $table): void {
            $table->id();
            $table->string('key')->unique();
            $table->string('title');
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->foreignId('og_image_id')->nullable()->constrained('curator')->nullOnDelete();
            $table->boolean('noindex')->default(false);
            $table->string('banner_eyebrow')->nullable();
            $table->string('banner_title')->nullable();
            $table->string('banner_highlight')->nullable();
            $table->text('banner_subtitle')->nullable();
            $table->foreignId('banner_image_id')->nullable()->constrained('curator')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('menus', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('location')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('menu_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('menu_id')->constrained('menus')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('menu_items')->cascadeOnDelete();
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('badge', 30)->nullable();
            $table->string('column_title', 80)->nullable();
            $table->boolean('is_mega')->default(false);
            $table->string('url')->nullable();
            $table->string('icon')->nullable();
            $table->boolean('open_in_new_tab')->default(false);
            $table->unsignedInteger('position')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['menu_id', 'parent_id', 'position']);
        });

        Schema::create('mini_apps', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('icon')->nullable();
            $table->text('description')->nullable();
            $table->string('link')->nullable();
            $table->string('badge')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('ad_banners', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('slot')->index();
            $table->foreignId('image_id')->nullable()->constrained('curator')->nullOnDelete();
            $table->string('link')->nullable();
            $table->string('alt_text')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('open_new_tab')->default(true);
            $table->integer('order')->default(0);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();
        });

        Schema::create('testimonials', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('role')->nullable();
            $table->text('quote');
            $table->foreignId('avatar_id')->nullable()->constrained('curator')->nullOnDelete();
            $table->unsignedTinyInteger('rating')->default(5);
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
        Schema::dropIfExists('ad_banners');
        Schema::dropIfExists('mini_apps');
        Schema::dropIfExists('menu_items');
        Schema::dropIfExists('menus');
        Schema::dropIfExists('pages');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('post_categories');
    }
};
