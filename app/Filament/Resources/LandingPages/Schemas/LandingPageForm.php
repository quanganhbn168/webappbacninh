<?php

namespace App\Filament\Resources\LandingPages\Schemas;

use App\Enums\LandingTemplate;
use Closure;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class LandingPageForm
{
    public static function configure(Schema $schema): Schema
    {
        $is = fn (LandingTemplate $template): Closure => fn (Get $get): bool => $get('template') === $template->value;

        return $schema->components([
            Tabs::make('Landing page')->id('landing-form-tabs')->persistTab()->columnSpanFull()->tabs([
                Tab::make('Thông tin chung')->schema([
                    Section::make('Trang')->schema([
                        TextInput::make('name')->label('Tên trang (để bạn nhận biết)')->required()->maxLength(255),
                        TextInput::make('slug')->label('Đường dẫn')->required()->maxLength(120)->alphaDash()->unique(ignoreRecord: true)
                            ->prefix('/landing/')->helperText('Chữ thường, số và dấu gạch ngang. Đổi đường dẫn sẽ làm link cũ không còn dùng được.'),
                        Select::make('template')->label('Mẫu giao diện')->options(LandingTemplate::options())->required()->native(false)->live()
                            ->disabled(fn (string $operation): bool => $operation !== 'create')->dehydrated()
                            ->helperText('Chọn một lần khi tạo. Giao diện của mẫu cố định, bạn chỉ sửa nội dung.')
                            ->afterStateUpdated(function (?string $state, Set $set): void {
                                $template = LandingTemplate::tryFrom((string) $state);
                                if (! $template) {
                                    return;
                                }
                                // A new template starts from its own sample text, price and SEO.
                                foreach ($template->sample() as $field => $value) {
                                    $set($field, $value);
                                }
                                $set('content', $template->defaults());
                            }),
                        Toggle::make('is_active')->label('Đang hiển thị')->default(true),
                        Toggle::make('noindex')->label('Không cho Google lập chỉ mục')->default(true)->helperText('Nên bật với trang chỉ chạy quảng cáo. Tắt nếu muốn trang xuất hiện khi tìm kiếm.'),
                    ])->columns(2),
                    Section::make('Giá')->schema([
                        TextInput::make('price')->label('Giá bán (đ)')->numeric()->required()->minValue(0)->helperText('Nhập số, ví dụ 3990000.'),
                        TextInput::make('compare_price')->label('Giá gốc (đ)')->numeric()->minValue(0)->helperText('Để trống nếu không giảm giá. % giảm được tính tự động.'),
                    ])->columns(2),
                    Section::make('Đo lường quảng cáo')->schema([
                        Textarea::make('head_code')->label('Mã đo lường riêng của trang')->rows(6)->maxLength(20000)
                            ->extraInputAttributes(['spellcheck' => 'false', 'class' => 'font-mono'])
                            ->helperText('Pixel, thẻ Google Ads… của riêng chiến dịch này, dán nguyên đoạn. Mã trong Cài đặt (GA4) đã tự chạy, không dán lại. Quản trị viên đang đăng nhập không được đếm.'),
                    ])->columns(1),
                    Section::make('SEO & chia sẻ')->schema([
                        TextInput::make('meta_title')->label('Tiêu đề trang')->maxLength(255),
                        Textarea::make('meta_description')->label('Mô tả')->rows(2)->maxLength(500),
                    ])->columns(1),
                ]),
                Tab::make('Nội dung trang')->schema([
                    Group::make(PurehomeFields::make())->visible($is(LandingTemplate::Purehome))->columnSpanFull(),
                    Group::make(FreshairFields::make())->visible($is(LandingTemplate::Freshair))->columnSpanFull(),
                ]),
            ]),
        ]);
    }
}
