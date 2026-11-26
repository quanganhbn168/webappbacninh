<?php

namespace App\Filament\Resources\Posts\Tables;

use App\Domain\Seo\AnalyzePost;
use App\Domain\Seo\StoreSeoScore;
use App\Models\Post;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('Tiêu đề')->searchable()->sortable()->wrap(),
                TextColumn::make('category.name')->label('Danh mục')->searchable(),
                TextColumn::make('data.seo_score')->label('SEO')->badge()->placeholder('Chưa chấm')
                    ->color(fn (int $state): string => match (AnalyzePost::grade($state)) {
                        'good' => 'success',
                        'fair' => 'warning',
                        default => 'danger',
                    }),
                TextColumn::make('author.name')->label('Tác giả')->placeholder(fn (): string => (string) site_config('name')),
                TextColumn::make('created_at')->label('Ngày tạo')->date('d/m/Y')->sortable(),
                TextColumn::make('published_at')->label('Xuất bản')->date('d/m/Y')->placeholder('Chưa xuất bản')->sortable(),
                TextColumn::make('content_updated_at')->label('Cập nhật')->date('d/m/Y')->placeholder('—')->sortable(),
                IconColumn::make('is_published')->label('Đã xuất bản')->boolean(),
                IconColumn::make('is_featured')->label('Nổi bật')->boolean(),
            ])
            ->filters([
                TernaryFilter::make('is_published')->label('Đã xuất bản'),
                TernaryFilter::make('is_featured')->label('Nổi bật'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('seoScore')->label('Chấm lại SEO')->icon('heroicon-o-chart-bar')
                        ->action(fn (Collection $records) => $records->each(fn (Post $post) => app(StoreSeoScore::class)->execute($post)))
                        ->deselectRecordsAfterCompletion()
                        ->successNotificationTitle('Đã chấm lại điểm SEO'),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
