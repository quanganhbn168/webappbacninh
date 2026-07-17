<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('templates', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('template_category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('image')->nullable();
            $table->string('category')->nullable();
            $table->string('type')->nullable();
            $table->string('industry')->nullable();
            $table->string('demo_url')->nullable();
            $table->decimal('price', 15)->nullable();
            $table->decimal('sale_price', 15)->nullable();
            $table->unsignedSmallInteger('year')->nullable();
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->string('badge')->nullable();
            $table->string('duration')->nullable();
            $table->json('data')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_premium')->default(false);
            $table->boolean('is_free')->default(false);
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('templates');
    }
};
