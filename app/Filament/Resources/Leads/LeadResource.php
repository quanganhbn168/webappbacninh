<?php

namespace App\Filament\Resources\Leads;

use App\Filament\NavigationGroup;
use App\Models\Lead;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class LeadResource extends Resource
{
    protected static ?string $model = Lead::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-inbox';

    protected static string|\UnitEnum|null $navigationGroup = NavigationGroup::Customers;

    protected static ?string $navigationLabel = 'Yêu cầu liên hệ';

    protected static ?string $modelLabel = 'yêu cầu liên hệ';

    protected static ?string $pluralModelLabel = 'Yêu cầu liên hệ';

    protected static ?int $navigationSort = 10;

    /** Number of requests nobody has handled yet, shown next to the sidebar item. */
    public static function getNavigationBadge(): ?string
    {
        $new = Lead::query()->where('status', 'new')->count();

        return $new > 0 ? (string) $new : null;
    }

    public static function getNavigationBadgeColor(): string
    {
        return 'danger';
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->label('Họ tên'),
            TextInput::make('phone')->label('Điện thoại'),
            TextInput::make('email')->label('Email'),
            TextInput::make('company')->label('Doanh nghiệp'),
            TextInput::make('need')->label('Dịch vụ quan tâm'),
            TextInput::make('budget')->label('Ngân sách'),
            TextInput::make('timeline')->label('Thời gian dự kiến'),
            Textarea::make('message')->label('Nội dung')->rows(6)->columnSpanFull(),
            TextInput::make('source')->label('Trang gửi yêu cầu')->columnSpanFull(),
            TextEntry::make('campaign')->label('Thông số quảng cáo')->columnSpanFull()->listWithLineBreaks()
                ->state(fn (?Lead $record): array => collect($record?->meta['campaign'] ?? [])->map(fn (string $value, string $key): string => $key.': '.$value)->values()->all())
                ->placeholder('Không đến từ liên kết quảng cáo'),
        ]);
    }

    public static function table(Table $table): Table
    {
        $statuses = ['new' => 'Mới', 'contacted' => 'Đã liên hệ', 'closed' => 'Đã xử lý'];

        return $table->columns([
            TextColumn::make('name')->label('Họ tên')->searchable(),
            TextColumn::make('phone')->label('Điện thoại')->searchable()->copyable(),
            TextColumn::make('email')->label('Email')->searchable()->toggleable(),
            TextColumn::make('need')->label('Nhu cầu')->limit(40),
            TextColumn::make('campaign')->label('Quảng cáo')->state(fn (Lead $record): ?string => $record->campaignLabel())->placeholder('—')->toggleable(),
            SelectColumn::make('status')->label('Trạng thái')->options($statuses)->rules(['in:new,contacted,closed']),
            TextColumn::make('created_at')->label('Ngày gửi')->dateTime('d/m/Y H:i')->sortable(),
        ])->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')->label('Trạng thái')->options($statuses),
                SelectFilter::make('landing_page_id')->label('Landing page')->relationship('landingPage', 'name'),
            ])
            ->recordActions([ViewAction::make()]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListLeads::route('/')];
    }
}
