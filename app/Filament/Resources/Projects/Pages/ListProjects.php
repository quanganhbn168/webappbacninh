<?php

namespace App\Filament\Resources\Projects\Pages;

use App\Filament\Resources\Projects\ProjectResource;
use App\Settings\ProjectSettings;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Support\Icons\Heroicon;

class ListProjects extends ListRecords
{
    protected static string $resource = ProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->settingsAction(),
            CreateAction::make(),
        ];
    }

    private function settingsAction(): Action
    {
        return Action::make('projectSettings')
            ->label('Cấu hình dự án')
            ->icon(Heroicon::OutlinedCog6Tooth)
            ->color('gray')
            ->modalHeading('Cấu hình dự án')
            ->modalSubmitActionLabel('Lưu')
            ->fillForm(fn (): array => app(ProjectSettings::class)->refresh()->toArray())
            ->schema([
                TextInput::make('code_prefix')->label('Tiền tố mã')->maxLength(20)->placeholder('DA-')->live(onBlur: true),
                TextInput::make('code_next')->label('Số tiếp theo')->integer()->required()->minValue(1)->maxValue(999999999)->live(onBlur: true)
                    ->helperText('Số đã có dự án dùng sẽ tự được bỏ qua.'),
                TextInput::make('code_digits')->label('Số chữ số tối thiểu')->integer()->required()->minValue(1)->maxValue(8)->live(onBlur: true)
                    ->helperText('3 chữ số: 49 thành 049.'),
                TextInput::make('code_suffix')->label('Hậu tố mã')->maxLength(20)->placeholder('-2026')->live(onBlur: true)
                    ->helperText(fn (Get $get): string => 'Ví dụ mã: '.ProjectSettings::formatCode(
                        (string) $get('code_prefix'),
                        (int) $get('code_next'),
                        min(8, max(1, (int) $get('code_digits'))),
                        (string) $get('code_suffix'),
                    )),
                TagsInput::make('deliverable_presets')->label('Hạng mục bàn giao cố định')->reorderable()
                    ->placeholder('Gõ rồi nhấn Enter')
                    ->helperText('Hiện thành các ô tích trong form dự án.'),
            ])
            ->action(function (array $data): void {
                app(ProjectSettings::class)->fill([
                    'code_prefix' => (string) $data['code_prefix'],
                    'code_suffix' => (string) $data['code_suffix'],
                    'code_digits' => (int) $data['code_digits'],
                    'code_next' => (int) $data['code_next'],
                    'deliverable_presets' => array_values($data['deliverable_presets'] ?? []),
                ])->save();

                Notification::make()->title('Đã lưu cấu hình dự án')->success()->send();
            });
    }
}
