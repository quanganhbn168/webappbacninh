<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('theme_features', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });
        Schema::create('template_theme_feature', function (Blueprint $table): void {
            $table->foreignId('template_id')->constrained()->cascadeOnDelete();
            $table->foreignId('theme_feature_id')->constrained()->cascadeOnDelete();
            $table->primary(['template_id', 'theme_feature_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('template_theme_feature');
        Schema::dropIfExists('theme_features');
    }
};
