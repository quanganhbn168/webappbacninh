<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('operation_services', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('menu_key')->nullable();
            $table->string('eyebrow')->nullable();
            $table->string('highlight')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('secondary_image')->nullable();
            $table->string('icon')->nullable();
            $table->string('price_from')->nullable();
            $table->string('cadence')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->json('data')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('operation_services');
    }
};
