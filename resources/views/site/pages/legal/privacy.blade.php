@extends('layouts.site')

@section('content')
    <x-legal-document
        title="Chính sách bảo mật thông tin"
        eyebrow="THÔNG TIN VÀ CHÍNH SÁCH"
        summary="WebApp Bắc Ninh tôn trọng quyền riêng tư của khách hàng. Chính sách này giải thích loại thông tin có thể được tiếp nhận, mục đích sử dụng và cách khách hàng yêu cầu kiểm tra hoặc điều chỉnh dữ liệu đã cung cấp."
        notice="Website chỉ thu thập thông tin do khách hàng chủ động gửi qua biểu mẫu, điện thoại, email hoặc các kênh liên hệ được công bố."
        icon="shield-check"
        updated="2026-07-11"
        :sections="[
            ['heading' => '1. Thông tin được tiếp nhận', 'content' => 'Tùy từng biểu mẫu hoặc nội dung trao đổi, WebApp Bắc Ninh có thể tiếp nhận các thông tin sau:', 'items' => ['Họ tên, số điện thoại, email và tên doanh nghiệp.', 'Lĩnh vực hoạt động, nhu cầu thiết kế website hoặc sử dụng dịch vụ.', 'Mức ngân sách, thời gian triển khai và nội dung khách hàng tự nguyện cung cấp.', 'Thông tin kỹ thuật cơ bản như trang gửi biểu mẫu, nguồn truy cập hoặc mã chiến dịch khi được cấu hình.']],
            ['heading' => '2. Mục đích sử dụng thông tin', 'content' => 'Thông tin được sử dụng trong phạm vi cần thiết để:', 'items' => ['Liên hệ tư vấn, khảo sát yêu cầu và lập báo giá.', 'Triển khai, bàn giao, bảo hành và hỗ trợ dịch vụ đã thống nhất.', 'Gửi thông báo liên quan trực tiếp đến dự án hoặc hợp đồng.', 'Phân tích hiệu quả website và cải thiện trải nghiệm khách hàng.', 'Phòng ngừa hành vi spam, giả mạo hoặc sử dụng website sai mục đích.']],
            ['heading' => '3. Phạm vi chia sẻ thông tin', 'content' => 'WebApp Bắc Ninh không bán dữ liệu khách hàng. Thông tin chỉ có thể được chia sẻ trong các trường hợp cần thiết sau:', 'items' => ['Nhân sự hoặc đối tác kỹ thuật trực tiếp tham gia dự án và cần dữ liệu để hoàn thành công việc.', 'Đơn vị cung cấp hạ tầng như hosting, email hoặc công cụ lưu trữ theo phạm vi vận hành hệ thống.', 'Cơ quan có thẩm quyền khi có yêu cầu hợp lệ theo quy định pháp luật.', 'Trường hợp khách hàng đã đồng ý bằng văn bản hoặc qua nội dung trao đổi có thể xác minh.']],
            ['heading' => '4. Thời gian lưu trữ', 'content' => 'Thông tin được lưu trong thời gian cần thiết để tư vấn, thực hiện dự án, bảo hành, đối soát hoặc đáp ứng nghĩa vụ liên quan. Dữ liệu không còn cần thiết có thể được xóa hoặc ẩn danh theo quy trình nội bộ.', 'items' => []],
            ['heading' => '5. Biện pháp bảo vệ dữ liệu', 'content' => 'WebApp Bắc Ninh áp dụng các biện pháp phù hợp với quy mô hệ thống, bao gồm phân quyền truy cập, sao lưu, sử dụng kết nối bảo mật, cập nhật phần mềm và hạn chế lưu dữ liệu nhạy cảm không cần thiết.', 'items' => []],
            ['heading' => '6. Quyền của khách hàng', 'content' => 'Khách hàng có thể liên hệ để yêu cầu:', 'items' => ['Kiểm tra thông tin đã cung cấp.', 'Điều chỉnh thông tin chưa chính xác.', 'Rút lại yêu cầu tư vấn hoặc đề nghị xóa dữ liệu khi không còn nghĩa vụ liên quan.', 'Giải thích về mục đích và phạm vi sử dụng dữ liệu.']],
            ['heading' => '7. Cookie và công cụ đo lường', 'content' => 'Website có thể sử dụng cookie hoặc công cụ đo lường để ghi nhận lượt truy cập, thiết bị, nguồn truy cập và hành vi sử dụng ở mức tổng hợp. Khách hàng có thể điều chỉnh cookie trong trình duyệt; một số chức năng đo lường có thể không hoạt động đầy đủ sau khi tắt.', 'items' => []],
            ['heading' => '8. Thay đổi chính sách', 'content' => 'Chính sách có thể được cập nhật để phù hợp với cách vận hành website và yêu cầu thực tế. Phiên bản mới được công bố tại trang này và ghi rõ ngày cập nhật.', 'items' => []],
        ]"
    />
@endsection
