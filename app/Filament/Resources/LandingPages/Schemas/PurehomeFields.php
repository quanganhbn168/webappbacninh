<?php

namespace App\Filament\Resources\LandingPages\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

/** Admin fields of the PureHome layout, grouped the way the page reads from top to bottom. */
final class PurehomeFields
{
    /** @return array<int, \Filament\Schemas\Components\Section> */
    public static function make(): array
    {
        $item = fn (): array => [LandingFields::title(), LandingFields::text2('text', 'Nội dung (xuống dòng để ngắt dòng)')];

        return [
            LandingFields::section('Thương hiệu & liên hệ', [
                LandingFields::text('brand', 'Tên thương hiệu'),
                LandingFields::text('brand_tagline', 'Khẩu hiệu'),
                LandingFields::text('product', 'Tên sản phẩm (ngắn)'),
                LandingFields::text('hotline', 'Hotline'),
                LandingFields::text('hotline_hours', 'Giờ làm việc'),
                LandingFields::text('email', 'Email nhận đơn'),
                LandingFields::area('address', 'Địa chỉ'),
            ], collapsed: false),
            LandingFields::section('Đầu trang (hero)', [
                LandingFields::text('hero_eyebrow', 'Dòng nhỏ phía trên'),
                LandingFields::area('hero_title', 'Tiêu đề lớn'),
                LandingFields::text('hero_subtitle', 'Tiêu đề phụ'),
                LandingFields::area('hero_description', 'Mô tả', 3),
                LandingFields::area('hero_note', 'Chữ viết tay cạnh sản phẩm'),
                LandingFields::list('assurances', 'Cam kết dưới nút mua (3 ô)', [LandingFields::text2('text', 'Nội dung')], 'text'),
                LandingFields::list('lifestyle', 'Ba ý ngắn trên ảnh', [LandingFields::title(), TextInput::make('text')->label('Dòng phụ')->maxLength(255)], 'title'),
            ]),
            LandingFields::section('Ưu điểm', [
                LandingFields::text('benefits_title', 'Tiêu đề khối'),
                LandingFields::list('benefits', 'Các ưu điểm', $item(), 'title'),
            ]),
            LandingFields::section('Sản phẩm & công nghệ', [
                LandingFields::area('product_note', 'Chữ viết tay trên ảnh sản phẩm'),
                LandingFields::list('design_features', 'Điểm thiết kế (3 ô trên ảnh)', $item(), 'title'),
                LandingFields::area('tech_title', 'Tiêu đề công nghệ'),
                LandingFields::area('tech_intro', 'Giới thiệu công nghệ', 3),
                LandingFields::list('filters', 'Các lớp lọc / bộ phận', [LandingFields::title(), TextInput::make('text')->label('Mô tả')->maxLength(255)], 'title'),
            ]),
            LandingFields::section('Vì sao nên chọn', [
                LandingFields::text('reasons_title', 'Tiêu đề khối'),
                LandingFields::text('reasons_subtitle', 'Dòng phụ'),
                LandingFields::list('reasons', 'Các lý do', $item(), 'title'),
            ]),
            LandingFields::section('Đánh giá khách hàng', [
                LandingFields::text('reviews_title', 'Tiêu đề khối'),
                LandingFields::text('reviews_subtitle', 'Dòng phụ'),
                LandingFields::list('reviews', 'Các đánh giá', [
                    LandingFields::title('name', 'Tên khách'),
                    TextInput::make('city')->label('Tỉnh / thành')->maxLength(100),
                    LandingFields::title('quote', 'Câu nổi bật'),
                    LandingFields::avatar(),
                    Textarea::make('text')->label('Nội dung đánh giá')->rows(3)->required()->maxLength(1000)->columnSpanFull(),
                ], 'name'),
            ]),
            LandingFields::section('Ưu đãi & đặt mua', [
                LandingFields::text('offer_title', 'Tiêu đề ưu đãi'),
                LandingFields::area('offer_text', 'Mô tả ưu đãi'),
                LandingFields::lines('perks', 'Quyền lợi kèm theo (3 ô)'),
                LandingFields::text('order_note', 'Dòng nhắc trong hộp đặt mua'),
            ]),
            LandingFields::section('Câu hỏi thường gặp', [
                LandingFields::text('faq_title', 'Tiêu đề khối'),
                LandingFields::text('faq_subtitle', 'Dòng phụ'),
                LandingFields::list('faqs', 'Câu hỏi', [
                    LandingFields::title('question', 'Câu hỏi'),
                    Textarea::make('answer')->label('Trả lời')->rows(3)->required()->maxLength(1500)->columnSpanFull(),
                ], 'question', 'Thêm câu hỏi'),
            ]),
            LandingFields::section('Chân trang & chính sách', [
                LandingFields::area('footer_about', 'Giới thiệu ngắn', 3),
                LandingFields::text('footer_copy', 'Dòng bản quyền'),
                LandingFields::text('footer_slogan', 'Khẩu hiệu cuối trang'),
                LandingFields::area('policy_warranty', 'Chính sách bảo hành', 3),
                LandingFields::area('policy_returns', 'Chính sách đổi trả', 3),
                LandingFields::area('policy_shipping', 'Chính sách giao hàng', 3),
                LandingFields::area('policy_privacy', 'Chính sách bảo mật', 3),
                LandingFields::area('policy_terms', 'Điều khoản sử dụng', 3),
            ]),
            LandingFields::section('Hình ảnh', [
                LandingFields::image('hero_image', 'Ảnh nền đầu trang', 'Ngang, khoảng 1536 × 768; chữ nằm bên trái nên để trống nửa trái.'),
                LandingFields::image('detail_image', 'Ảnh chi tiết sản phẩm', 'Vuông, khoảng 1024 × 1024.'),
                LandingFields::image('filters_image', 'Ảnh cấu tạo / lớp lọc', 'Vuông, khoảng 1024 × 1024.'),
                LandingFields::image('share_image', 'Ảnh chia sẻ (Zalo, Facebook)', 'Ngang 1200 × 630.'),
            ], collapsed: false),
        ];
    }
}
