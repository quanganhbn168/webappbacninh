<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->foreignId('featured_media_id')->nullable()->constrained('curator')->nullOnDelete();
            $table->foreignId('og_media_id')->nullable()->constrained('curator')->nullOnDelete();
        });
        Schema::table('post_categories', function (Blueprint $table) {
            $table->foreignId('image_id')->nullable()->constrained('curator')->nullOnDelete();
            $table->foreignId('og_media_id')->nullable()->constrained('curator')->nullOnDelete();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropConstrainedForeignId('featured_media_id');
            $table->dropConstrainedForeignId('og_media_id');
        });
        Schema::table('post_categories', function (Blueprint $table) {
            $table->dropConstrainedForeignId('image_id');
            $table->dropConstrainedForeignId('og_media_id');
            $table->dropColumn(['meta_title', 'meta_description']);
        });
    }
};
