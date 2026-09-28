<?php

namespace App\Filament\Resources\Pages\Tables;

use App\Enums\PageTemplate;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class PagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('order')
            ->columns([
                TextColumn::make('title')->label('Tiêu đề')->searchable()->sortable(),
                TextColumn::make('slug')->label('Đường dẫn')->prefix('/')->searchable(),
                TextColumn::make('template')->label('Kiểu trang')->badge()
                    ->formatStateUsing(fn (PageTemplate $state): string => $state->label()),
                TextColumn::make('order')->label('Thứ tự')->sortable(),
                IconColumn::make('is_active')->label('Hiển thị')->boolean(),
                TextColumn::make('updated_at')->label('Cập nhật')->dateTime('d/m/Y H:i')->sortable(),
            ])
            ->filters([
                SelectFilter::make('template')->label('Kiểu trang')->options(PageTemplate::options()),
                TernaryFilter::make('is_active')->label('Hiển thị'),
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
