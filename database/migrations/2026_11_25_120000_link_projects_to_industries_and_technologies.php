<?php

use App\Models\Technology;
use App\Models\TemplateCategory;
use App\Support\ShieldPermissions;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Projects pick several industries (shared with the theme catalog) and several
 * technologies with logos, instead of free text. Existing values are carried over.
 */
return new class extends Migration
{
    /** name => [icon, brand colour] */
    private const TECHNOLOGIES = [
        'PHP' => ['php', '#777BB4'],
        'Laravel' => ['laravel', '#FF2D20'],
        'MySQL' => ['mysql', '#4479A1'],
        'HTML5' => ['html5', '#E34F26'],
        'CSS' => ['css', '#663399'],
        'JavaScript' => ['javascript', '#F7DF1E'],
        'TypeScript' => ['typescript', '#3178C6'],
        'Tailwind CSS' => ['tailwindcss', '#06B6D4'],
        'Bootstrap' => ['bootstrap', '#7952B3'],
        'Sass' => ['sass', '#CC6699'],
        'Alpine.js' => ['alpinedotjs', '#8BC0D0'],
        'Livewire' => ['livewire', '#4E56A6'],
        'Filament' => ['filament', '#FDAE4B'],
        'Vue.js' => ['vuedotjs', '#4FC08D'],
        'React' => ['react', '#61DAFB'],
        'Next.js' => ['nextdotjs', '#000000'],
        'Node.js' => ['nodedotjs', '#5FA04E'],
        'Vite' => ['vite', '#9135FF'],
        'WordPress' => ['wordpress', '#21759B'],
        'WooCommerce' => ['woocommerce', '#96588A'],
        'Shopify' => ['shopify', '#7AB55C'],
        'Flutter' => ['flutter', '#02569B'],
        'Redis' => ['redis', '#FF4438'],
        'Nginx' => ['nginx', '#009639'],
        'Docker' => ['docker', '#2496ED'],
        'Cloudflare' => ['cloudflare', '#F38020'],
        'Google Analytics' => ['googleanalytics', '#E37400'],
        'Figma' => ['figma', '#F24E1E'],
    ];

    public function up(): void
    {
        Schema::create('technologies', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('icon')->nullable();
            $table->string('color', 9)->nullable();
            $table->foreignId('image_id')->nullable()->constrained('curator')->nullOnDelete();
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });

        Schema::create('project_technology', function (Blueprint $table) {
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('technology_id')->constrained()->cascadeOnDelete();
            $table->primary(['project_id', 'technology_id']);
        });

        Schema::create('project_template_category', function (Blueprint $table) {
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('template_category_id')->constrained()->cascadeOnDelete();
            $table->primary(['project_id', 'template_category_id']);
        });

        $order = 0;
        foreach (self::TECHNOLOGIES as $name => [$icon, $color]) {
            Technology::query()->create(['name' => $name, 'icon' => 'brand-'.$icon, 'color' => $color, 'order' => $order += 10]);
        }

        foreach (DB::table('projects')->get(['id', 'industry', 'technologies']) as $project) {
            // "Du lịch - Dịch vụ" and "Sản xuất, Thương mại" become two industries each.
            foreach (preg_split('/\s*[,;\/|]\s*|\s+[-–—]\s+/u', (string) $project->industry, -1, PREG_SPLIT_NO_EMPTY) as $name) {
                if (trim($name) !== '') {
                    DB::table('project_template_category')->insertOrIgnore([
                        'project_id' => $project->id,
                        'template_category_id' => TemplateCategory::findOrCreateByName($name)->id,
                    ]);
                }
            }

            foreach (json_decode((string) $project->technologies, true) ?: [] as $name) {
                if (is_string($name) && trim($name) !== '') {
                    DB::table('project_technology')->insertOrIgnore([
                        'project_id' => $project->id,
                        'technology_id' => Technology::findOrCreateByName($name)->id,
                    ]);
                }
            }
        }

        // The old values stay for reference; the column name is now the relationship's.
        Schema::table('projects', fn (Blueprint $table) => $table->renameColumn('technologies', 'legacy_technologies'));

        ShieldPermissions::grantToSuperAdmin(['Technology']);
    }

    public function down(): void
    {
        ShieldPermissions::revoke(['Technology']);
        Schema::table('projects', fn (Blueprint $table) => $table->renameColumn('legacy_technologies', 'technologies'));
        Schema::dropIfExists('project_template_category');
        Schema::dropIfExists('project_technology');
        Schema::dropIfExists('technologies');
    }
};
