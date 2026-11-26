<?php

namespace App\Filament\Forms;

use App\Domain\Content\Faqs;
use Closure;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\RichEditor\RichContentRenderer;
use Illuminate\Support\HtmlString;

/**
 * One box for all common questions: paste the whole text, each question on its own line with
 * its answer below. Saved as the [{q, a}] list of App\Domain\Content\Faqs.
 */
class FaqEditor extends RichEditor
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label('Câu hỏi thường gặp')
            ->toolbarButtons([['bold', 'italic', 'link'], ['h3', 'bulletList', 'orderedList'], ['undo', 'redo']])
            ->extraAttributes(['class' => 'rich-editor-scroll'])
            // The note under the box lists what was recognised, refreshed on leaving it.
            ->live(onBlur: true)
            ->helperText(fn (FaqEditor $component): HtmlString => $component->summary())
            ->dehydrateStateUsing(fn (mixed $state): array => Faqs::parse(self::htmlOf($state))['items'])
            // Nothing typed here is dropped silently: saving stops until every part belongs to a question.
            ->rule(fn (): Closure => function (string $attribute, mixed $value, Closure $fail): void {
                if ($problems = Faqs::parse(self::htmlOf($value))['problems']) {
                    $fail($problems[0]);
                }
            });
    }

    public function getDefaultStateCasts(): array
    {
        return [app(FaqStateCast::class), ...parent::getDefaultStateCasts()];
    }

    /** The editor's state, a document tree while editing, as HTML. */
    public static function htmlOf(mixed $state): string
    {
        if (is_array($state)) {
            return isset($state['type']) ? RichContentRenderer::make($state)->toUnsafeHtml() : Faqs::toHtml(array_values($state));
        }

        return (string) $state;
    }

    private function summary(): HtmlString
    {
        $items = Faqs::parse(self::htmlOf($this->getRawState()))['items'];

        if ($items === []) {
            return new HtmlString('Dán cả khối vào đây: mỗi câu hỏi một dòng (kết thúc bằng dấu ? hoặc in đậm), câu trả lời ở dưới. Link trong câu trả lời được giữ nguyên.');
        }

        return new HtmlString('Đã nhận ra '.count($items).' câu hỏi:<br>'.implode('<br>', array_map(
            fn (int $index, array $item): string => ($index + 1).'. '.e($item['q']),
            array_keys($items), $items,
        )));
    }
}
