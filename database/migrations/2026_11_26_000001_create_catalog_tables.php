<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Theme catalog and projects, with the industries and technologies they share. */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_categories', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('technologies', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique();
            $table->string('icon')->nullable();
            $table->string('color', 9)->nullable();
            $table->foreignId('image_id')->nullable()->constrained('curator')->nullOnDelete();
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });

        Schema::create('template_categories', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('template_categories')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->json('faqs')->nullable();
            $table->foreignId('banner_image_id')->nullable()->constrained('curator')->nullOnDelete();
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });

        Schema::create('theme_features', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('templates', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('template_category_id')->nullable()->constrained('template_categories')->nullOnDelete();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('image_id')->nullable()->constrained('curator')->nullOnDelete();
            $table->json('gallery')->nullable();
            $table->string('type')->nullable();
            $table->string('demo_url')->nullable();
            $table->string('demo_layout', 32)->nullable();
            $table->json('demo')->nullable();
            $table->decimal('price', 15, 2)->nullable();
            $table->decimal('sale_price', 15, 2)->nullable();
            $table->unsignedSmallInteger('year')->nullable();
            $table->text('description')->nullable();
            $table->string('badge')->nullable();
            $table->string('duration')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->json('tags')->nullable();
            $table->json('audiences')->nullable();
            $table->json('pages')->nullable();
            $table->json('included_features')->nullable();
            $table->json('customizations')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
        });

        Schema::create('template_theme_feature', function (Blueprint $table): void {
            $table->foreignId('template_id')->constrained('templates')->cascadeOnDelete();
            $table->foreignId('theme_feature_id')->constrained('theme_features')->cascadeOnDelete();
            $table->primary(['template_id', 'theme_feature_id']);
        });

        Schema::create('projects', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('project_category_id')->nullable()->constrained('project_categories')->nullOnDelete();
            $table->string('code')->nullable()->unique();
            $table->string('title');
            $table->string('slug')->unique();
            $table->foreignId('image_id')->nullable()->constrained('curator')->nullOnDelete();
            $table->text('excerpt')->nullable();
            $table->string('link')->nullable();
            $table->unsignedSmallInteger('year')->nullable();
            $table->string('client')->nullable();
            $table->string('duration')->nullable();
            $table->string('cost')->nullable();
            $table->longText('challenge')->nullable();
            $table->longText('solution')->nullable();
            $table->json('gallery')->nullable();
            $table->json('results')->nullable();
            $table->json('deliverables')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
        });

        Schema::create('project_technology', function (Blueprint $table): void {
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('technology_id')->constrained('technologies')->cascadeOnDelete();
            $table->primary(['project_id', 'technology_id']);
        });

        Schema::create('project_template_category', function (Blueprint $table): void {
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('template_category_id')->constrained('template_categories')->cascadeOnDelete();
            $table->primary(['project_id', 'template_category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_template_category');
        Schema::dropIfExists('project_technology');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('template_theme_feature');
        Schema::dropIfExists('templates');
        Schema::dropIfExists('theme_features');
        Schema::dropIfExists('template_categories');
        Schema::dropIfExists('technologies');
        Schema::dropIfExists('project_categories');
    }
};
