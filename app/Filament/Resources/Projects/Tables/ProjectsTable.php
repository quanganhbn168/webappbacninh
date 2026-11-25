<?php

namespace App\Filament\Resources\Projects\Tables;

use App\Models\Project;
use Awcodes\Curator\Components\Tables\CuratorColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
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
                TextColumn::make('code')->label('Mã')->searchable()->sortable(),
                TextColumn::make('title')
                    ->label('Dự án')
                    ->wrap()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category.name')->label('Nhóm')->wrap()->sortable(),
                TextColumn::make('industries.name')->label('Ngành')->badge()->searchable(),
                ToggleColumn::make('is_featured')->label('Nổi bật'),
                ToggleColumn::make('is_active')->label('Hiển thị'),
                TextColumn::make('order')->label('Thứ tự')->sortable(),
                TextColumn::make('updated_at')->label('Cập nhật')->dateTime('d/m/Y H:i')->sortable()
                    ->description(fn (Project $record): ?string => $record->created_at ? 'Tạo: '.$record->created_at->format('d/m/Y H:i') : null),
            ])
            ->filters([
                SelectFilter::make('project_category_id')->label('Nhóm')->relationship('category', 'name'),
                SelectFilter::make('industries')->label('Ngành')->relationship('industries', 'name')->multiple()->preload(),
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
