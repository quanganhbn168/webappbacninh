<?php

namespace App\Domain\Landing;

/** Starting content of the FreshAir layout. Every value can be edited in the admin; a new landing page begins from these. */
final class FreshairDefaults
{
    /** @return array{name: string, meta_title: string, meta_description: string, price: int, compare_price: int} */
    public static function sample(): array
    {
        return [
            'name' => 'Máy lọc không khí FreshAir S8',
            'meta_title' => 'FreshAir S8 — Không khí sạch, cuộc sống tốt đẹp hơn | FreshAir Mall',
            'meta_description' => 'Máy lọc không khí FreshAir S8: HEPA H13, lọc 360°, vận hành êm ái. Giá ưu đãi 3.590.000đ, freeship toàn quốc, quà tặng và bảo hành 24 tháng.',
            'price' => 3590000,
            'compare_price' => 5990000,
        ];
    }

    /** @return array<string, mixed> */
    public static function content(): array
    {
        return [
            'brand' => 'FreshAir Mall',
            'brand_tagline' => 'Không khí sạch. Cuộc sống xanh',
            'product' => 'FreshAir S8',
            'product_full' => 'Máy lọc không khí FreshAir S8',
            'hotline' => '1900 636 888',
            'hotline_hours' => '8:00 - 22:00',
            'email' => 'cskh@freshair.vn',

            'sale_label' => 'SIÊU SALE HÔM NAY',
            'sale_text_a' => 'GIẢM ĐẾN 40%',
            'sale_text_b' => 'FREESHIP TOÀN QUỐC',

            'hero_kicker' => 'Hít thở sạch hơn. Sống khỏe hơn.',
            'hero_title' => "Không khí sạch\nCho cuộc sống\ntốt đẹp hơn",
            'hero_strong' => 'Bảo vệ sức khỏe gia đình bạn',
            'hero_text' => 'mỗi ngày với FreshAir S8.',
            'hero_note' => "Sạch từng hơi thở\nYêu thương dài lâu ♡",
            'hero_icons' => [
                ['a' => 'Lọc bụi mịn', 'b' => 'PM2.5'],
                ['a' => 'Khử mùi', 'b' => 'hiệu quả'],
                ['a' => 'Lọc sạch', 'b' => 'đến 99,97%'],
                ['a' => 'Không khí', 'b' => 'trong lành'],
            ],
            'hero_benefits' => [
                ['title' => 'Lọc PM2.5 hiệu quả', 'text' => 'Đến 99,97% bụi mịn'],
                ['title' => 'Khử mùi & VOCs', 'text' => 'Mùi hôi, khói, hóa chất'],
                ['title' => 'Vận hành êm ái', 'text' => 'Độ ồn chỉ từ 22 dB'],
                ['title' => 'Tiết kiệm điện', 'text' => 'Công suất chỉ 28W'],
                ['title' => 'Phù hợp mọi không gian', 'text' => 'Phòng ngủ, phòng khách, văn phòng'],
                ['title' => 'Bảo hành 24 tháng', 'text' => 'Yên tâm sử dụng'],
            ],

            'badge_hot' => 'Bán chạy',
            'badge_trust' => 'Thương hiệu được tin chọn',
            'tagline' => 'Không khí sạch hơn · Cuộc sống khỏe mạnh hơn',
            'rating' => '4.9',
            'review_count' => '2.4k',
            'sold_count' => '8.7k',
            'price_label' => 'GIÁ SIÊU SALE HÔM NAY',
            'price_label_sub' => 'Ưu đãi độc quyền',
            'price_note' => "Giá tốt cho\ncuộc sống xanh",
            'vouchers' => [
                ['code' => 'FRESH300', 'title' => 'Giảm 300K', 'note' => 'Đơn từ 3.5 triệu'],
                ['code' => 'FRESH10', 'title' => 'Giảm 10%', 'note' => 'Tối đa 200K'],
                ['code' => 'FREESHIP', 'title' => 'FREESHIP', 'note' => 'Toàn quốc'],
            ],
            'stock_text' => 'Sẵn hàng · Giao ngay',
            'buy_perks' => [
                ['title' => 'Giao nhanh toàn quốc', 'text' => 'Nhận hàng 1 - 3 ngày'],
                ['title' => 'Đổi trả 7 ngày', 'text' => 'Nếu có lỗi từ nhà sản xuất'],
                ['title' => 'Hàng chính hãng 100%', 'text' => 'Có đầy đủ hóa đơn, bảo hành'],
            ],
            'trust' => [
                ['title' => 'Giao nhanh toàn quốc', 'text' => 'Nhận hàng 1 - 3 ngày'],
                ['title' => 'Hàng chính hãng 100%', 'text' => 'Có đầy đủ hóa đơn, bảo hành'],
                ['title' => 'Đổi trả 7 ngày', 'text' => 'Nếu có lỗi từ nhà sản xuất'],
                ['title' => 'Thanh toán khi nhận hàng', 'text' => 'An toàn · Tiện lợi'],
            ],
            'family_count' => '8.7K',
            'family_text' => 'GIA ĐÌNH ĐÃ TIN DÙNG FRESHAIR S8',

            'filters_title' => 'Công nghệ lọc tiên tiến',
            'filters_subtitle' => 'Bảo vệ toàn diện cho gia đình bạn',
            'filters' => [
                ['title' => 'Màng lọc thô', 'text' => 'Giữ bụi lớn, lông thú'],
                ['title' => 'Màng lọc HEPA H13', 'text' => 'Lọc 99,97% bụi mịn PM2.5, vi khuẩn, phấn hoa'],
                ['title' => 'Than hoạt tính', 'text' => 'Khử mùi, VOCs, khí độc'],
                ['title' => 'Màng lọc bổ trợ', 'text' => 'Không khí sạch hơn, trong lành hơn'],
            ],
            'specs' => [
                ['label' => 'Model', 'value' => 'FreshAir S8'],
                ['label' => 'Diện tích sử dụng', 'value' => '20 - 50 m²'],
                ['label' => 'CADR (Lưu lượng khí sạch)', 'value' => '320 m³/h'],
                ['label' => 'Màng lọc', 'value' => 'HEPA H13 + Than hoạt tính + Màng lọc thô'],
                ['label' => 'Độ ồn', 'value' => '22 - 50 dB'],
                ['label' => 'Công suất', 'value' => '28W'],
                ['label' => 'Kích thước', 'value' => '250 × 250 × 520 mm'],
                ['label' => 'Trọng lượng', 'value' => '4.8 kg'],
                ['label' => 'Màu sắc', 'value' => 'Trắng'],
                ['label' => 'Bảo hành', 'value' => '24 tháng'],
            ],
            'specs_note' => 'Chọn đúng công suất cho không gian của bạn',
            'consult_title' => 'Đăng ký tư vấn miễn phí',
            'consult_text' => 'Để lại thông tin, chuyên gia của chúng tôi sẽ giúp bạn chọn sản phẩm phù hợp.',
            'consult_rooms' => [['label' => 'Phòng khách'], ['label' => 'Phòng ngủ'], ['label' => 'Văn phòng'], ['label' => 'Cần tư vấn thêm']],

            'gifts_title' => 'Combo ưu đãi / Quà tặng kèm',
            'gifts_subtitle' => 'Mua máy lọc không khí FreshAir S8 ngay hôm nay để nhận những phần quà giá trị',
            'gift_1_title' => 'Bộ lọc HEPA H13',
            'gift_1_value' => '890.000đ',
            'gift_1_note' => 'Thay trong 6 - 12 tháng',
            'gift_2_title' => 'Nhiệt ẩm kế thông minh',
            'gift_2_value' => '350.000đ',
            'gift_2_note' => 'Theo dõi chất lượng không khí',
            'gift_voucher_title' => 'Giảm thêm 10%',
            'gift_voucher_note' => 'Cho lần mua tiếp theo',
            'gift_voucher_code' => 'FRESH10',
            'gift_message_title' => 'QUÀ TẶNG GIÁ TRỊ!',
            'gift_message_text' => "Đồng hành cùng\nbầu không khí sạch!",

            'why_title' => 'Vì sao nên chọn FreshAir?',
            'why' => [
                ['title' => 'Lọc sạch 99,97%', 'text' => 'Bụi mịn PM2.5'],
                ['title' => 'Khử mùi vượt trội', 'text' => 'Mùi hôi, VOCs, khói thuốc'],
                ['title' => 'Bảo vệ sức khỏe', 'text' => 'Cho cả gia đình'],
                ['title' => 'Tiết kiệm điện', 'text' => 'Công suất chỉ 28W'],
                ['title' => 'Vận hành êm ái', 'text' => 'Ngủ ngon, sống khỏe'],
                ['title' => 'Thương hiệu uy tín', 'text' => 'Hàng chính hãng 100%'],
            ],

            'reviews_title' => 'Đánh giá khách hàng',
            'reviews' => [
                ['avatar' => 'lan', 'name' => 'Nguyễn Thị Mai', 'text' => 'Máy chạy rất êm, lọc không khí tốt. Phòng ngủ thoáng hơn hẳn, bé nhà mình cũng ít ho hơn.'],
                ['avatar' => 'hoang', 'name' => 'Trần Văn Hùng', 'text' => 'Thiết kế đẹp, dễ sử dụng. Màn hình hiển thị rất trực quan. Giao hàng nhanh, đóng gói cẩn thận.'],
                ['avatar' => 'huong', 'name' => 'Lê Thu Hà', 'text' => 'Không khí trong lành hơn rõ rệt, mùi thức ăn cũng được khử nhanh. Rất đáng mua!'],
                ['avatar' => 'hoang', 'name' => 'Phạm Quang Minh', 'text' => 'Sản phẩm chất lượng, gia đình mình rất hài lòng. Sẽ giới thiệu cho bạn bè.'],
            ],

            'faq_title' => 'Câu hỏi thường gặp (FAQ)',
            'faq_subtitle' => 'Giải đáp những thắc mắc phổ biến về máy lọc không khí FreshAir S8',
            'faqs' => [
                ['question' => 'Máy lọc không khí FreshAir S8 phù hợp với diện tích bao nhiêu?', 'answer' => 'FreshAir S8 phù hợp cho không gian 20 - 50 m². Đặt máy ở vị trí thông thoáng và tránh che kín cửa hút, thoát khí để máy hoạt động hiệu quả.'],
                ['question' => 'Chính sách bảo hành như thế nào?', 'answer' => 'FreshAir S8 được bảo hành chính hãng 24 tháng. Giữ hóa đơn hoặc thông tin đặt hàng và liên hệ hotline 1900 636 888 khi cần hỗ trợ.'],
                ['question' => 'Màng lọc HEPA H13 có thể sử dụng trong bao lâu?', 'answer' => 'Thời gian thay màng lọc tham khảo là 6 - 12 tháng, tùy chất lượng không khí và tần suất sử dụng. Kiểm tra màng lọc định kỳ theo hướng dẫn sử dụng.'],
                ['question' => 'Tôi có thể đổi trả hàng không?', 'answer' => 'Bạn được hỗ trợ đổi trả trong 7 ngày nếu sản phẩm có lỗi từ nhà sản xuất. Liên hệ FreshAir trước khi gửi lại sản phẩm để được hướng dẫn.'],
                ['question' => 'Máy có tốn nhiều điện không?', 'answer' => 'Máy có công suất 28W, phù hợp sử dụng hàng ngày. Điện năng tiêu thụ thực tế phụ thuộc chế độ và thời gian hoạt động.'],
                ['question' => 'Thời gian giao hàng mất bao lâu?', 'answer' => 'Thời gian giao hàng dự kiến 1 - 3 ngày tùy khu vực. FreshAir xác nhận thời gian cụ thể cùng bạn khi chốt đơn hàng.'],
            ],

            'footer_text' => "Đồng hành cùng bầu không khí sạch\ncho mọi gia đình Việt.",
            'cart_note' => 'Miễn phí giao hàng toàn quốc. Liên hệ FreshAir để xác nhận ưu đãi, địa chỉ và thời gian nhận hàng.',
            'share_title' => 'FreshAir S8 — Không khí sạch, cuộc sống tốt đẹp hơn',

            'hero_image' => null,
            'product_image' => null,
            'filters_image' => null,
            'share_image' => null,
        ];
    }
}
