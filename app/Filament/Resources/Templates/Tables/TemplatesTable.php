<?php

namespace App\Filament\Resources\Templates\Tables;

use App\Enums\TemplateType;
use Awcodes\Curator\Components\Tables\CuratorColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class TemplatesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('order')
            ->reorderable('order')
            ->columns([
                CuratorColumn::make('image')->label('Ảnh')->size(48),
                TextColumn::make('code')->label('Mã')->searchable()->sortable(),
                TextColumn::make('name')->label('Tên giao diện')->searchable()->wrap(),
                TextColumn::make('category.name')->label('Ngành')->sortable(),
                TextColumn::make('type')->label('Loại')->badge()
                    ->formatStateUsing(fn (?TemplateType $state): string => $state?->label() ?? ''),
                TextColumn::make('price')->label('Giá từ')->numeric(locale: 'vi')->suffix('đ')->sortable(),
                IconColumn::make('is_featured')->label('Nổi bật')->boolean(),
                IconColumn::make('is_active')->label('Hiển thị')->boolean(),
            ])
            ->filters([
                SelectFilter::make('template_category_id')->label('Ngành')->relationship('category', 'name'),
                SelectFilter::make('type')->label('Loại website')->options(TemplateType::options()),
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
