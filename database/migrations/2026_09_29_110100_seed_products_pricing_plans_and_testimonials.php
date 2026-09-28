<?php

use App\Domain\Media\Actions\ImportLocalImage;
use Database\Seeders\PricingPlanSeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\TestimonialSeeder;
use Illuminate\Database\Migrations\Migration;

// The home, products and pricing pages read these tables, so a deploy fills them with the
// content that used to be written in the views. Seeders skip rows that already exist.
return new class extends Migration
{
    public function up(): void
    {
        $images = app(ImportLocalImage::class);
        (new ProductSeeder)->run($images);
        (new PricingPlanSeeder)->run();
        (new TestimonialSeeder)->run($images);
    }

    public function down(): void {}
};
