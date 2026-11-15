<?php

namespace App\Filament\Resources\LandingPages\Tables;

use App\Enums\LandingTemplate;
use App\Models\LandingPage;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ReplicateAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class LandingPagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('name')->label('Trang')->searchable()->description(fn (LandingPage $record): string => '/landing/'.$record->slug),
                TextColumn::make('template')->label('Mẫu')->badge()->formatStateUsing(fn (LandingTemplate $state): string => Str::before($state->label(), ' (')),
                TextColumn::make('price')->label('Giá')->state(fn (LandingPage $record): string => $record->money().'đ'),
                IconColumn::make('is_active')->label('Hiển thị')->boolean(),
                TextColumn::make('updated_at')->label('Cập nhật')->since()->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
                ReplicateAction::make()->label('Nhân bản')
                    ->excludeAttributes(['slug'])
                    ->beforeReplicaSaved(function (LandingPage $replica, LandingPage $record): void {
                        $replica->slug = $record->slug.'-'.Str::lower(Str::random(4));
                        $replica->name = $record->name.' (bản sao)';
                        $replica->is_active = false;
                    }),
                Action::make('view')->label('Xem trang')->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (LandingPage $record): string => $record->url())->openUrlInNewTab(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
