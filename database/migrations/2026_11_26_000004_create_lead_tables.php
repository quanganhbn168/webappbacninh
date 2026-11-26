<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Landing pages that run ads and the contact requests of the whole site. */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('landing_pages', function (Blueprint $table): void {
            $table->id();
            $table->string('slug')->unique();
            $table->string('template', 32);
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->boolean('noindex')->default(false);
            $table->text('head_code')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->unsignedBigInteger('price')->default(0);
            $table->unsignedBigInteger('compare_price')->nullable();
            $table->json('content');
            $table->timestamps();
        });

        Schema::create('leads', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('phone', 30);
            $table->string('email')->nullable();
            $table->string('company')->nullable();
            $table->string('need')->nullable();
            $table->string('budget')->nullable();
            $table->string('timeline')->nullable();
            $table->text('message')->nullable();
            $table->string('source')->nullable();
            $table->foreignId('landing_page_id')->nullable()->constrained('landing_pages')->nullOnDelete();
            $table->string('status')->default('new')->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
        Schema::dropIfExists('landing_pages');
    }
};
