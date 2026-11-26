<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Services, products and the one price table they read. */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_categories', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('services', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('service_category_id')->nullable()->constrained('service_categories')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->foreignId('image_id')->nullable()->constrained('curator')->nullOnDelete();
            $table->foreignId('secondary_image_id')->nullable()->constrained('curator')->nullOnDelete();
            $table->boolean('is_landing')->default(false);
            $table->string('section', 20)->default('website')->index();
            $table->string('menu_key')->nullable();
            $table->string('eyebrow')->nullable();
            $table->string('highlight')->nullable();
            $table->string('icon')->nullable();
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->string('timeline')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('show_in_grid')->default(false);
            $table->string('card_title', 60)->nullable();
            $table->string('card_text', 120)->nullable();
            $table->timestamps();
            $table->string('cta')->nullable();
            $table->string('need_value')->nullable();
            $table->json('audiences')->nullable();
            $table->json('problems')->nullable();
            $table->json('pages')->nullable();
            $table->json('features')->nullable();
            $table->json('faqs')->nullable();
        });

        Schema::create('operation_services', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->foreignId('image_id')->nullable()->constrained('curator')->nullOnDelete();
            $table->foreignId('secondary_image_id')->nullable()->constrained('curator')->nullOnDelete();
            $table->string('menu_key')->nullable();
            $table->string('eyebrow')->nullable();
            $table->string('highlight')->nullable();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->string('cadence')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->string('cta')->nullable();
            $table->string('need_value')->nullable();
            $table->json('audiences')->nullable();
            $table->json('scope')->nullable();
            $table->json('deliverables')->nullable();
            $table->json('process')->nullable();
            $table->json('faqs')->nullable();
        });

        Schema::create('products', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('group', 30)->index();
            $table->foreignId('image_id')->nullable()->constrained('curator')->nullOnDelete();
            $table->text('summary')->nullable();
            $table->json('tags')->nullable();
            $table->string('detail_url')->nullable();
            $table->string('demo_url')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('pricing_plans', function (Blueprint $table): void {
            $table->id();
            $table->string('group', 30)->nullable()->index();
            $table->boolean('show_in_table')->default(true);
            $table->string('name');
            $table->string('icon', 60)->nullable();
            $table->string('price_prefix', 30)->nullable();
            $table->string('price', 60);
            $table->string('price_suffix', 30)->nullable();
            $table->text('summary')->nullable();
            $table->json('features')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->string('badge', 60)->nullable();
            $table->string('cta_label', 60)->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('priceables', function (Blueprint $table): void {
            $table->foreignId('pricing_plan_id')->constrained('pricing_plans')->cascadeOnDelete();
            $table->string('priceable_type');
            $table->unsignedBigInteger('priceable_id');
            $table->index(['priceable_type', 'priceable_id']);
            $table->unique(['pricing_plan_id', 'priceable_type', 'priceable_id'], 'priceables_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('priceables');
        Schema::dropIfExists('pricing_plans');
        Schema::dropIfExists('products');
        Schema::dropIfExists('operation_services');
        Schema::dropIfExists('services');
        Schema::dropIfExists('service_categories');
    }
};
