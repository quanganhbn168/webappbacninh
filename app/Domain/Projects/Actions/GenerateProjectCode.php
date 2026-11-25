<?php

namespace App\Domain\Projects\Actions;

use App\Models\Project;
use App\Settings\ProjectSettings;
use Illuminate\Support\Facades\Cache;

final class GenerateProjectCode
{
    /** Takes the next free code and moves the counter past it. */
    public function execute(): string
    {
        return Cache::lock('project-code', 10)->block(5, function (): string {
            $settings = app(ProjectSettings::class)->refresh();
            [$number, $code] = $this->nextFree($settings);

            $settings->code_next = $number + 1;
            $settings->save();

            return $code;
        });
    }

    /** The code the next project would get, without taking it. */
    public function preview(): string
    {
        return $this->nextFree(app(ProjectSettings::class))[1];
    }

    /** @return array{int, string} Numbers already used by a project are skipped. */
    private function nextFree(ProjectSettings $settings): array
    {
        $number = max(1, $settings->code_next);

        while (true) {
            $code = ProjectSettings::formatCode($settings->code_prefix, $number, $settings->code_digits, $settings->code_suffix);

            if (! Project::query()->where('code', $code)->exists()) {
                return [$number, $code];
            }

            $number++;
        }
    }
}
