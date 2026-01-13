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
        Schema::create('thumbnail_logs', function (Blueprint $table) {
            $table->id();
            $table->string('provider'); // 'youtube' hoặc 'tiktok'
            $table->text('original_url'); // Link người dùng dán vào
            $table->string('title')->nullable(); // Tiêu đề video
            $table->text('thumbnail_url'); // Link ảnh thumbnail
            $table->string('saved_path'); // Đường dẫn lưu trên server
            $table->timestamps(); // Tự động tạo cột created_at và updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thumbnail_logs');
    }
};
