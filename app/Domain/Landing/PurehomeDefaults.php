<?php

namespace App\Domain\Landing;

/** Starting content of the PureHome layout. Every value can be edited in the admin; a new landing page begins from these. */
final class PurehomeDefaults
{
    /** @return array{name: string, meta_title: string, meta_description: string, price: int, compare_price: int} */
    public static function sample(): array
    {
        return [
            'name' => 'Máy lọc không khí PureHome A3',
            'meta_title' => 'Máy lọc không khí PureHome A3 — Không khí trong lành cho cả gia đình',
            'meta_description' => 'PureHome A3 với hệ thống lọc 4 lớp, màng lọc HEPA H13, vận hành êm ái. Không khí sạch hơn, cuộc sống xanh hơn. Giá ưu đãi 3.990.000đ.',
            'price' => 3990000,
            'compare_price' => 5990000,
        ];
    }

    /** @return array<string, mixed> */
    public static function content(): array
    {
        return [
            'brand' => 'PureHome',
            'brand_tagline' => 'Không khí sạch, cuộc sống xanh',
            'product' => 'PureHome A3',
            'hotline' => '0900 000 000',
            'hotline_hours' => '8:00 - 21:00 hàng ngày',
            'email' => 'cskh@example.com',
            'address' => "Số 123 Đường An Phú,\nQuận 2, TP. Thủ Đức, TP. Hồ Chí Minh",

            'hero_eyebrow' => 'Hít thở sạch hơn, sống khỏe hơn',
            'hero_title' => "Máy lọc không khí\nPureHome A3",
            'hero_subtitle' => 'Không khí trong lành cho cả gia đình',
            'hero_description' => "Loại bỏ bụi mịn, vi khuẩn, khử mùi hiệu quả.\nMang đến không gian sống sạch sẽ, an toàn và tràn đầy năng lượng mỗi ngày.",
            'hero_note' => "Không khí sạch\nHạnh phúc trọn vẹn",
            'assurances' => [
                ['text' => "Miễn phí giao hàng\ntoàn quốc"],
                ['text' => "Bảo hành chính hãng\n24 tháng"],
                ['text' => "Đổi trả dễ dàng\ntrong 7 ngày"],
            ],
            'lifestyle' => [
                ['title' => 'Sạch hơn', 'text' => 'cho gia đình'],
                ['title' => 'Xanh hơn', 'text' => 'cho tương lai'],
                ['title' => 'Khỏe mạnh hơn', 'text' => 'mỗi ngày'],
            ],

            'benefits_title' => 'Không khí sạch mang lại cuộc sống tốt đẹp hơn',
            'benefits' => [
                ['title' => 'Lọc bụi mịn PM2.5', 'text' => "Loại bỏ đến 99,97%\nbụi mịn, vi khuẩn"],
                ['title' => 'Khử mùi hiệu quả', 'text' => "Giúp không gian luôn\nthoáng mát, dễ chịu"],
                ['title' => 'Vận hành êm ái', 'text' => "Độ ồn thấp, phù hợp\ncho giấc ngủ ngon"],
                ['title' => 'Tiết kiệm điện', 'text' => "Công nghệ hiện đại,\nhiệu suất cao"],
                ['title' => 'Phù hợp mọi không gian', 'text' => "Lý tưởng cho phòng ngủ,\nphòng khách, văn phòng"],
            ],

            'product_note' => "Từng chi tiết\nvì bầu không khí tốt hơn",
            'design_features' => [
                ['title' => 'Thiết kế hiện đại', 'text' => "Tinh tế, phù hợp\nmọi không gian"],
                ['title' => 'Màn hình cảm ứng', 'text' => 'Dễ dàng thao tác'],
                ['title' => 'Cửa hút khí 360°', 'text' => "Lọc sạch không khí\ntừ mọi hướng"],
            ],
            'tech_title' => "Công nghệ tiên tiến\ntrong từng lớp lọc",
            'tech_intro' => 'PureHome A3 được trang bị hệ thống lọc 4 lớp,'."\n".'loại bỏ hiệu quả bụi mịn, vi khuẩn, virus và các tác nhân gây dị ứng, mang đến bầu không khí sạch và an toàn cho cả gia đình.',
            'filters' => [
                ['title' => 'Màng lọc thô', 'text' => 'Giữ lại tóc, lông thú, bụi lớn'],
                ['title' => 'Màng lọc HEPA H13', 'text' => 'Loại bỏ 99,97% bụi mịn PM2.5, vi khuẩn, virus'],
                ['title' => 'Màng lọc than hoạt tính', 'text' => 'Khử mùi, hấp thụ khí độc hại (VOC, formaldehyde...)'],
                ['title' => 'Màng lọc bổ trợ', 'text' => 'Giúp không khí thêm trong lành, tươi mát'],
            ],

            'reasons_title' => 'Vì sao nên chọn PureHome A3?',
            'reasons_subtitle' => 'Sự lựa chọn tin cậy của hàng nghìn gia đình Việt',
            'reasons' => [
                ['title' => 'Chất lượng đáng tin cậy', 'text' => "Sản phẩm đạt tiêu chuẩn\nquốc tế, an toàn cho sức khỏe."],
                ['title' => 'Bảo vệ cả gia đình', 'text' => "Không gian sống trong lành\ncho trẻ nhỏ, người lớn tuổi\nvà người nhạy cảm."],
                ['title' => 'Thiết kế hiện đại', 'text' => "Tinh tế, sang trọng,\nhài hòa với mọi không gian\nnội thất."],
                ['title' => 'Vì một tương lai xanh', 'text' => "Góp phần giảm ô nhiễm,\nbảo vệ môi trường sống\ncho thế hệ mai sau."],
            ],

            'reviews_title' => 'Khách hàng nói gì về PureHome A3?',
            'reviews_subtitle' => 'Hàng nghìn gia đình đã tin tưởng và lựa chọn PureHome',
            'reviews' => [
                ['avatar' => 'lan', 'quote' => 'Không khí trong lành hơn hẳn!', 'text' => 'Từ khi có PureHome A3, bé nhà mình ít hắt hơi, ngủ ngon hơn. Máy chạy rất êm, thiết kế đẹp, dễ sử dụng.', 'name' => 'Nguyễn Thị Lan', 'city' => 'Hà Nội'],
                ['avatar' => 'hoang', 'quote' => 'Sản phẩm rất đáng mua', 'text' => 'Không gian phòng khách luôn thoáng mát, hết mùi đồ ăn. Máy hoạt động êm, tiết kiệm điện. Rất hài lòng!', 'name' => 'Trần Minh Hoàng', 'city' => 'TP. Hồ Chí Minh'],
                ['avatar' => 'huong', 'quote' => 'Tốt cho sức khỏe gia đình', 'text' => 'Mình bị dị ứng bụi, từ khi dùng máy thấy cải thiện rõ rệt. Không khí dễ chịu hơn, ngủ ngon hơn nhiều.', 'name' => 'Lê Thu Hương', 'city' => 'Đà Nẵng'],
            ],

            'offer_title' => 'Ưu đãi đặc biệt hôm nay!',
            'offer_text' => "Sở hữu PureHome A3 với mức giá tốt nhất\nđể bảo vệ sức khỏe gia đình bạn.",
            'perks' => [
                ['text' => 'Miễn phí giao hàng toàn quốc'],
                ['text' => 'Bảo hành 24 tháng'],
                ['text' => 'Tặng kèm bộ lọc dự phòng'],
            ],
            'order_note' => 'Miễn phí giao hàng toàn quốc · Bảo hành 24 tháng · Đổi trả trong 7 ngày.',

            'faq_title' => 'Câu hỏi thường gặp',
            'faq_subtitle' => 'Giải đáp những thắc mắc phổ biến về PureHome A3',
            'faqs' => [
                ['question' => 'Máy lọc không khí PureHome A3 phù hợp cho diện tích bao nhiêu?', 'answer' => 'PureHome A3 phù hợp với phòng ngủ, phòng khách và văn phòng. Liên hệ hotline 0900 000 000 để được tư vấn theo diện tích và cách bố trí không gian của bạn.'],
                ['question' => 'Có tốn nhiều điện không?', 'answer' => 'Máy sử dụng công nghệ tiết kiệm điện và có thể điều chỉnh chế độ hoạt động theo nhu cầu. Mức điện năng thực tế phụ thuộc vào chế độ và thời gian sử dụng.'],
                ['question' => 'Máy có gây tiếng ồn khi hoạt động không?', 'answer' => 'PureHome A3 vận hành êm ái, phù hợp sử dụng khi làm việc hoặc nghỉ ngơi. Chọn chế độ thấp khi sử dụng trong phòng ngủ.'],
                ['question' => 'Sản phẩm có bảo hành không?', 'answer' => 'Sản phẩm được bảo hành chính hãng 24 tháng. Vui lòng giữ thông tin mua hàng và liên hệ 0900 000 000 khi cần hỗ trợ.'],
                ['question' => 'Bao lâu thì cần thay màng lọc?', 'answer' => 'Thời gian thay màng lọc phụ thuộc vào chất lượng không khí và tần suất sử dụng. Kiểm tra màng lọc định kỳ và thay theo hướng dẫn đi kèm sản phẩm.'],
                ['question' => 'Tôi có thể đổi trả nếu không hài lòng?', 'answer' => 'PureHome hỗ trợ đổi trả trong 7 ngày theo chính sách đổi trả. Vui lòng liên hệ hotline để được hướng dẫn về tình trạng sản phẩm và các điều kiện áp dụng.'],
            ],

            'footer_about' => 'PureHome - Vì một cuộc sống khỏe mạnh hơn cho mọi gia đình Việt. Chúng tôi cam kết mang đến những sản phẩm chất lượng, góp phần kiến tạo không gian sống trong lành và bền vững.',
            'footer_copy' => '© 2024 PureHome. Tất cả quyền được bảo lưu.',
            'footer_slogan' => 'Sống xanh mỗi ngày, cho ngày mai tươi sáng hơn.',

            'policy_warranty' => 'PureHome A3 được bảo hành chính hãng 24 tháng. Vui lòng giữ hóa đơn hoặc thông tin đặt hàng để được hỗ trợ kiểm tra sản phẩm và hướng dẫn bảo hành.',
            'policy_returns' => 'Hỗ trợ đổi trả trong 7 ngày. Vui lòng liên hệ PureHome trước khi gửi sản phẩm để được xác nhận điều kiện đổi trả và hướng dẫn đóng gói, vận chuyển.',
            'policy_shipping' => 'Miễn phí giao hàng toàn quốc cho PureHome A3. Thời gian giao hàng và địa chỉ nhận hàng được xác nhận trực tiếp khi đặt mua.',
            'policy_privacy' => 'Thông tin bạn để lại (họ tên, số điện thoại) chỉ dùng để liên hệ tư vấn và hỗ trợ đơn hàng. Trang này không xử lý thanh toán trực tuyến.',
            'policy_terms' => 'Nội dung trên trang giới thiệu sản phẩm PureHome A3. Vui lòng xác nhận thông số, ưu đãi, chính sách và thông tin giao hàng với nhân viên tư vấn trước khi đặt mua.',

            'hero_image' => null,
            'detail_image' => null,
            'filters_image' => null,
            'share_image' => null,
        ];
    }
}
