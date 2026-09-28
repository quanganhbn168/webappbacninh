<?php

namespace App\Filament\Resources\Testimonials\Tables;

use Awcodes\Curator\Components\Tables\CuratorColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TestimonialsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('order')
            ->reorderable('order')
            ->columns([
                CuratorColumn::make('avatar')->label('Ảnh')->size(40)->circular(),
                TextColumn::make('name')->label('Khách hàng')->searchable()->description(fn ($record): ?string => $record->role),
                TextColumn::make('quote')->label('Đánh giá')->limit(80)->wrap(),
                IconColumn::make('is_active')->label('Hiển thị')->boolean(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
