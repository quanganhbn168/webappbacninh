<?php

namespace App\Filament\Resources\Projects\Tables;

use Awcodes\Curator\Components\Tables\CuratorColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('order')
            ->columns([
                CuratorColumn::make('image')->label('Ảnh')->size(48),
                TextColumn::make('title')
                    ->label('Dự án')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category.name')->label('Nhóm')->sortable(),
                TextColumn::make('industry')->label('Ngành')->searchable(),
                TextColumn::make('year')->label('Năm')->sortable(),
                IconColumn::make('is_featured')->label('Nổi bật')->boolean(),
                IconColumn::make('is_active')->label('Hiển thị')->boolean(),
                TextColumn::make('order')->label('Thứ tự')->sortable(),
                TextColumn::make('updated_at')->label('Cập nhật')->dateTime('d/m/Y H:i')->sortable(),
            ])
            ->filters([
                SelectFilter::make('project_category_id')->label('Nhóm')->relationship('category', 'name'),
                TernaryFilter::make('is_active')->label('Hiển thị'),
                TernaryFilter::make('is_featured')->label('Nổi bật'),
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
