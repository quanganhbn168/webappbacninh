<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('project_category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('code')->nullable()->unique();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('excerpt')->nullable();
            $table->string('image')->nullable();
            $table->string('link')->nullable();
            $table->string('category')->nullable();
            $table->string('industry')->nullable();
            $table->unsignedSmallInteger('year')->nullable();
            $table->string('client')->nullable();
            $table->string('duration')->nullable();
            $table->string('website_type')->nullable();
            $table->longText('challenge')->nullable();
            $table->longText('solution')->nullable();
            $table->json('gallery')->nullable();
            $table->json('results')->nullable();
            $table->json('deliverables')->nullable();
            $table->json('technologies')->nullable();
            $table->json('data')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
