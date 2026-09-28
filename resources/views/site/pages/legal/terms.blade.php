@extends('layouts.site')

@section('content')
    <x-legal-document
        title="Điều khoản sử dụng website"
        eyebrow="THÔNG TIN VÀ CHÍNH SÁCH"
        summary="Khi truy cập và sử dụng website WebApp Bắc Ninh, người dùng được hiểu là đã đọc và đồng ý tuân thủ các điều khoản dưới đây. Các nội dung báo giá, phạm vi triển khai và nghĩa vụ cụ thể của từng dự án sẽ được xác nhận riêng."
        notice="Nội dung trên website mang tính giới thiệu và tham khảo. Báo giá cuối cùng chỉ có hiệu lực khi phạm vi công việc được hai bên xác nhận."
        icon="file-pen-line"
        updated="2026-07-11"
        :sections="[
            ['heading' => '1. Phạm vi áp dụng', 'content' => 'Điều khoản này áp dụng đối với việc truy cập website, xem kho giao diện, đọc bài viết, gửi biểu mẫu và trao đổi về các dịch vụ do WebApp Bắc Ninh giới thiệu.', 'items' => []],
            ['heading' => '2. Quyền sử dụng nội dung', 'content' => 'Người dùng được phép xem, lưu liên kết và chia sẻ nội dung website cho mục đích tham khảo hợp pháp. Không được sao chép toàn bộ giao diện, mã nguồn, bài viết, hình ảnh hoặc tài liệu để kinh doanh, giả mạo thương hiệu hay cung cấp lại cho bên thứ ba khi chưa được chấp thuận.', 'items' => []],
            ['heading' => '3. Kho giao diện và nội dung minh họa', 'content' => 'Các mẫu giao diện, mức giá và thời gian triển khai trên website có thể là dữ liệu minh họa. Mẫu được chọn có thể được thay đổi màu sắc, nội dung, hình ảnh và chức năng theo phạm vi dự án. Việc chọn mẫu không tự động tạo thành hợp đồng hoặc cam kết triển khai.', 'items' => []],
            ['heading' => '4. Trách nhiệm của người dùng', 'content' => '', 'items' => ['Cung cấp thông tin trung thực khi gửi yêu cầu tư vấn.', 'Không gửi mã độc, nội dung vi phạm hoặc thực hiện hành vi gây gián đoạn website.', 'Không sử dụng thông tin liên hệ trên website để spam hoặc thực hiện hoạt động trái pháp luật.', 'Tự chịu trách nhiệm về quyền sử dụng đối với logo, hình ảnh, bài viết và dữ liệu bàn giao cho dự án.']],
            ['heading' => '5. Thông tin dịch vụ và báo giá', 'content' => 'Mức giá công bố là mức tham khảo theo cấu hình phổ biến. Chi phí thực tế phụ thuộc vào số lượng trang, dữ liệu, thiết kế riêng, chức năng, tích hợp, thời gian và các yêu cầu phát sinh. Báo giá chính thức sẽ ghi rõ phạm vi, thời hạn và điều kiện thanh toán.', 'items' => []],
            ['heading' => '6. Liên kết và dịch vụ của bên thứ ba', 'content' => 'Website có thể liên kết tới nền tảng bên thứ ba như bản đồ, mạng xã hội, công cụ phân tích hoặc cổng dịch vụ. WebApp Bắc Ninh không kiểm soát toàn bộ nội dung, chính sách hoặc sự ổn định của các hệ thống đó.', 'items' => []],
            ['heading' => '7. Giới hạn trách nhiệm', 'content' => 'WebApp Bắc Ninh cố gắng duy trì thông tin chính xác và website hoạt động ổn định nhưng không bảo đảm dịch vụ trực tuyến luôn không gián đoạn. Trách nhiệm đối với từng dự án được xác định theo báo giá, hợp đồng hoặc nội dung thống nhất cụ thể.', 'items' => []],
            ['heading' => '8. Thay đổi điều khoản', 'content' => 'Điều khoản có thể được cập nhật khi website, dịch vụ hoặc cách vận hành thay đổi. Người dùng nên kiểm tra phiên bản mới nhất tại trang này.', 'items' => []],
        ]"
    />
@endsection
