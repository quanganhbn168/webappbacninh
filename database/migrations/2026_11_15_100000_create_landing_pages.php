<?php

use App\Domain\Landing\FreshairDefaults;
use App\Domain\Landing\PurehomeDefaults;
use App\Support\ShieldPermissions;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->unsignedBigInteger('price')->default(0);
            $table->unsignedBigInteger('compare_price')->nullable();
            $table->json('content');
            $table->timestamps();
        });

        // The two sample pages that used to be hard-coded keep their addresses.
        $now = now();
        foreach ([
            ['mau-may-loc-khong-khi-mau-1', 'purehome', PurehomeDefaults::sample(), PurehomeDefaults::content()],
            ['mau-may-loc-khong-khi-mau-2', 'freshair', FreshairDefaults::sample(), FreshairDefaults::content()],
        ] as [$slug, $template, $sample, $content]) {
            DB::table('landing_pages')->insert($sample + [
                'slug' => $slug,
                'template' => $template,
                'is_active' => true,
                'noindex' => false,
                'content' => json_encode($content, JSON_UNESCAPED_UNICODE),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        ShieldPermissions::grantToSuperAdmin(['LandingPage']);
    }

    public function down(): void
    {
        Schema::dropIfExists('landing_pages');
        ShieldPermissions::revoke(['LandingPage']);
    }
};
