<?php

namespace App\Filament\Resources\LandingPages\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

/** Admin fields of the FreshAir layout, grouped the way the page reads from top to bottom. */
final class FreshairFields
{
    /** @return array<int, \Filament\Schemas\Components\Section> */
    public static function make(): array
    {
        $pair = fn (): array => [LandingFields::title(), TextInput::make('text')->label('Dòng phụ')->maxLength(255)];

        return [
            LandingFields::section('Thương hiệu & liên hệ', [
                LandingFields::text('brand', 'Tên cửa hàng / thương hiệu'),
                LandingFields::text('brand_tagline', 'Khẩu hiệu'),
                LandingFields::text('product', 'Tên sản phẩm (ngắn)'),
                LandingFields::text('product_full', 'Tên sản phẩm (đầy đủ)'),
                LandingFields::text('hotline', 'Hotline'),
                LandingFields::text('hotline_hours', 'Giờ làm việc'),
                LandingFields::text('email', 'Email nhận đơn'),
            ], collapsed: false),
            LandingFields::section('Dải khuyến mãi & đầu trang', [
                LandingFields::text('sale_label', 'Dải khuyến mãi — nhãn'),
                LandingFields::text('sale_text_a', 'Dải khuyến mãi — ý 1 (từ cuối được tô nổi)'),
                LandingFields::text('sale_text_b', 'Dải khuyến mãi — ý 2 (từ đầu được tô nổi)'),
                LandingFields::text('hero_kicker', 'Dòng nhỏ phía trên'),
                LandingFields::area('hero_title', 'Tiêu đề lớn (dòng đầu được tô nổi)', 3),
                LandingFields::text('hero_strong', 'Câu nhấn'),
                LandingFields::text('hero_text', 'Câu tiếp theo'),
                LandingFields::area('hero_note', 'Chữ viết tay'),
                LandingFields::list('hero_icons', 'Bốn biểu tượng trên ảnh', [LandingFields::title('a', 'Dòng 1'), TextInput::make('b')->label('Dòng 2')->maxLength(100)], 'a'),
                LandingFields::list('hero_benefits', 'Sáu ưu điểm bên phải', $pair(), 'title'),
            ], collapsed: false),
            LandingFields::section('Khối mua hàng', [
                LandingFields::text('badge_hot', 'Nhãn nổi bật'),
                LandingFields::text('badge_trust', 'Nhãn thương hiệu'),
                LandingFields::text('tagline', 'Dòng phụ dưới tên sản phẩm'),
                LandingFields::text('rating', 'Điểm đánh giá (ví dụ 4.9)'),
                LandingFields::text('review_count', 'Số đánh giá (ví dụ 2.4k)'),
                LandingFields::text('sold_count', 'Số đã bán (ví dụ 8.7k)'),
                LandingFields::text('price_label', 'Nhãn khung giá'),
                LandingFields::text('price_label_sub', 'Nhãn phụ khung giá'),
                LandingFields::area('price_note', 'Ghi chú cạnh giá'),
                LandingFields::text('stock_text', 'Tình trạng hàng'),
                LandingFields::list('vouchers', 'Voucher (bấm để chép mã)', [
                    LandingFields::title('code', 'Mã'),
                    LandingFields::title('title', 'Tên ưu đãi'),
                    TextInput::make('note')->label('Điều kiện')->maxLength(100),
                ], 'code', 'Thêm voucher'),
                LandingFields::list('buy_perks', 'Ba cam kết cạnh nút mua', $pair(), 'title'),
                LandingFields::list('trust', 'Bốn ô tin cậy', $pair(), 'title'),
                LandingFields::text('family_count', 'Số gia đình tin dùng'),
                LandingFields::text('family_text', 'Chữ dưới số gia đình'),
            ]),
            LandingFields::section('Công nghệ & thông số', [
                LandingFields::text('filters_title', 'Tiêu đề khối công nghệ'),
                LandingFields::text('filters_subtitle', 'Dòng phụ'),
                LandingFields::list('filters', 'Các lớp lọc', $pair(), 'title'),
                LandingFields::list('specs', 'Thông số kỹ thuật', [LandingFields::title('label', 'Tên thông số'), LandingFields::title('value', 'Giá trị')], 'label', 'Thêm thông số'),
                LandingFields::text('specs_note', 'Ghi chú dưới bảng thông số'),
            ]),
            LandingFields::section('Form tư vấn', [
                LandingFields::text('consult_title', 'Tiêu đề'),
                LandingFields::area('consult_text', 'Mô tả'),
                LandingFields::lines('consult_rooms', 'Các lựa chọn "Nhu cầu sử dụng"', 'Thêm lựa chọn', 'label'),
            ]),
            LandingFields::section('Quà tặng', [
                LandingFields::text('gifts_title', 'Tiêu đề khối'),
                LandingFields::text('gifts_subtitle', 'Dòng phụ'),
                LandingFields::text('gift_1_title', 'Quà 1 — tên'),
                LandingFields::text('gift_1_value', 'Quà 1 — trị giá'),
                LandingFields::text('gift_1_note', 'Quà 1 — ghi chú'),
                LandingFields::text('gift_2_title', 'Quà 2 — tên'),
                LandingFields::text('gift_2_value', 'Quà 2 — trị giá'),
                LandingFields::text('gift_2_note', 'Quà 2 — ghi chú'),
                LandingFields::text('gift_voucher_title', 'Voucher quà — tên (từ cuối được tô nổi)'),
                LandingFields::text('gift_voucher_note', 'Voucher quà — ghi chú'),
                LandingFields::text('gift_voucher_code', 'Voucher quà — mã'),
                LandingFields::text('gift_message_title', 'Lời nhắn — tiêu đề'),
                LandingFields::area('gift_message_text', 'Lời nhắn — nội dung'),
            ]),
            LandingFields::section('Vì sao chọn, đánh giá, hỏi đáp', [
                LandingFields::text('why_title', 'Tiêu đề "Vì sao chọn"'),
                LandingFields::list('why', 'Sáu lý do', $pair(), 'title'),
                LandingFields::text('reviews_title', 'Tiêu đề đánh giá'),
                LandingFields::list('reviews', 'Các đánh giá', [
                    LandingFields::title('name', 'Tên khách'),
                    LandingFields::avatar(),
                    Textarea::make('text')->label('Nội dung đánh giá')->rows(3)->required()->maxLength(1000)->columnSpanFull(),
                ], 'name'),
                LandingFields::text('faq_title', 'Tiêu đề hỏi đáp'),
                LandingFields::text('faq_subtitle', 'Dòng phụ hỏi đáp'),
                LandingFields::list('faqs', 'Câu hỏi', [
                    LandingFields::title('question', 'Câu hỏi'),
                    Textarea::make('answer')->label('Trả lời')->rows(3)->required()->maxLength(1500)->columnSpanFull(),
                ], 'question', 'Thêm câu hỏi'),
            ]),
            LandingFields::section('Chân trang, giỏ hàng, chia sẻ', [
                LandingFields::area('footer_text', 'Lời chân trang'),
                LandingFields::area('cart_note', 'Ghi chú trong giỏ hàng'),
                LandingFields::text('share_title', 'Tiêu đề khi chia sẻ'),
            ]),
            LandingFields::section('Hình ảnh', [
                LandingFields::image('hero_image', 'Ảnh nền đầu trang', 'Ngang, khoảng 1792 × 768; chữ nằm bên trái.'),
                LandingFields::image('product_image', 'Ảnh sản phẩm', 'Vuông, khoảng 1254 × 1254, nền sáng.'),
                LandingFields::image('filters_image', 'Ảnh cấu tạo / lớp lọc', 'Vuông, khoảng 1254 × 1254.'),
                LandingFields::image('share_image', 'Ảnh chia sẻ (Zalo, Facebook)', 'Ngang 1200 × 630.'),
            ], collapsed: false),
        ];
    }
}
