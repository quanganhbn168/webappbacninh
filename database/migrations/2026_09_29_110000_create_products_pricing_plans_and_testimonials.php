<?php

use App\Support\ShieldPermissions;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
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
            $table->string('group', 30)->index();
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

        ShieldPermissions::grantToSuperAdmin(['Product', 'PricingPlan', 'Testimonial', 'MiniApp', 'AdBanner']);
    }

    public function down(): void
    {
        ShieldPermissions::revoke(['Product', 'PricingPlan', 'Testimonial']);
        Schema::dropIfExists('testimonials');
        Schema::dropIfExists('pricing_plans');
        Schema::dropIfExists('products');
    }
};
