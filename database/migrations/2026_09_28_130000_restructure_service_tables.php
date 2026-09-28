<?php

use App\Domain\Media\Actions\ImportLocalImage;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Service landing content moves out of the untyped `data` JSON (and the
 * JSON-in-`content` features list) into typed columns; images move to
 * Curator. `is_landing` replaces matching slugs against config files.
 */
return new class extends Migration
{
    private const LISTS = [
        'services' => ['audiences', 'problems', 'pages', 'features', 'packages', 'faqs'],
        'operation_services' => ['audiences', 'scope', 'deliverables', 'process', 'packages', 'faqs'],
    ];

    public function up(): void
    {
        foreach (self::LISTS as $table => $lists) {
            Schema::table($table, function (Blueprint $blueprint) use ($table, $lists): void {
                if ($table === 'services') {
                    $blueprint->boolean('is_landing')->default(false)->after('slug');
                }
                $blueprint->foreignId('image_id')->nullable()->after('slug')->constrained('curator')->nullOnDelete();
                $blueprint->foreignId('secondary_image_id')->nullable()->after('image_id')->constrained('curator')->nullOnDelete();
                $blueprint->string('cta')->nullable();
                $blueprint->string('need_value')->nullable();
                foreach ($lists as $list) {
                    $blueprint->json($list)->nullable();
                }
            });

            $this->moveData($table, $lists);

            Schema::table($table, function (Blueprint $blueprint): void {
                $blueprint->dropColumn(['image', 'secondary_image', 'data']);
            });
        }
    }

    public function down(): void
    {
        foreach (self::LISTS as $table => $lists) {
            Schema::table($table, function (Blueprint $blueprint): void {
                $blueprint->string('image')->nullable();
                $blueprint->string('secondary_image')->nullable();
                $blueprint->json('data')->nullable();
            });

            Schema::table($table, function (Blueprint $blueprint) use ($table, $lists): void {
                $blueprint->dropConstrainedForeignId('image_id');
                $blueprint->dropConstrainedForeignId('secondary_image_id');
                $blueprint->dropColumn(array_merge(['cta', 'need_value'], $lists, $table === 'services' ? ['is_landing'] : []));
            });
        }
    }

    /**
     * @param  array<int, string>  $lists
     */
    private function moveData(string $table, array $lists): void
    {
        $images = app(ImportLocalImage::class);

        DB::table($table)->orderBy('id')->get()->each(function (object $service) use ($table, $lists, $images): void {
            $data = json_decode((string) $service->data, true) ?: [];
            $values = [
                'image_id' => $images->execute($service->image, 'services', $service->title)?->id,
                'secondary_image_id' => $images->execute($service->secondary_image, 'services', $service->title)?->id,
                'cta' => $data['cta'] ?? null,
                'need_value' => $data['need_value'] ?? null,
            ];

            foreach ($lists as $list) {
                $values[$list] = json_encode($data[$list] ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }

            if ($table === 'services') {
                // Landing services were the ones defined with a full data set.
                $values['is_landing'] = isset($data['packages']);
                $features = json_decode((string) $service->content, true);
                if (is_array($features) && array_is_list($features)) {
                    $values['features'] = json_encode($features, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                    $values['content'] = null;
                }
            }

            DB::table($table)->where('id', $service->id)->update($values);
        });
    }
};
