@extends('layouts.site')

@section('content')
    <x-legal-document
        title="Chính sách bảo hành và hỗ trợ"
        eyebrow="THÔNG TIN VÀ CHÍNH SÁCH"
        summary="Chính sách này mô tả nguyên tắc bảo hành đối với website và hạng mục kỹ thuật do WebApp Bắc Ninh trực tiếp triển khai. Thời hạn và phạm vi cụ thể của từng dự án sẽ được ghi trong báo giá hoặc biên bản bàn giao."
        notice="Bảo hành là sửa lỗi thuộc phạm vi đã bàn giao. Bổ sung chức năng, thay đổi bố cục hoặc nhập thêm nội dung là công việc phát sinh và được đánh giá riêng."
        icon="wrench"
        updated="2026-07-11"
        :sections="[
            ['heading' => '1. Thời điểm bắt đầu bảo hành', 'content' => 'Thời gian bảo hành được tính từ ngày nghiệm thu, bàn giao hoặc ngày website chính thức đưa vào sử dụng, tùy nội dung được hai bên xác nhận trong dự án.', 'items' => []],
            ['heading' => '2. Hạng mục được bảo hành', 'content' => '', 'items' => ['Lỗi hiển thị hoặc chức năng không hoạt động đúng với phạm vi đã nghiệm thu.', 'Lỗi phát sinh từ phần mã nguồn do WebApp Bắc Ninh trực tiếp xây dựng.', 'Điều chỉnh kỹ thuật nhỏ để khôi phục trạng thái hoạt động đã bàn giao.', 'Hướng dẫn lại thao tác quản trị cơ bản trong phạm vi hệ thống đã bàn giao.']],
            ['heading' => '3. Hạng mục không thuộc bảo hành', 'content' => '', 'items' => ['Lỗi do khách hàng hoặc bên thứ ba tự ý chỉnh sửa mã nguồn, cơ sở dữ liệu hoặc cấu hình máy chủ.', 'Lỗi do hosting, tên miền, email, API hoặc nền tảng bên thứ ba nằm ngoài phạm vi quản lý.', 'Mã độc, tấn công, mất mật khẩu hoặc sự cố do thiết bị và tài khoản quản trị của khách hàng.', 'Yêu cầu thêm trang, thêm chức năng, đổi toàn bộ bố cục hoặc thay đổi nghiệp vụ đã chốt.', 'Lỗi phát sinh từ nội dung, hình ảnh hoặc phần mềm không có bản quyền do khách hàng cung cấp.']],
            ['heading' => '4. Cách gửi yêu cầu hỗ trợ', 'content' => 'Khách hàng nên cung cấp đường dẫn gặp lỗi, ảnh chụp màn hình, thiết bị hoặc trình duyệt sử dụng và mô tả thao tác dẫn đến lỗi. Yêu cầu có đủ thông tin sẽ được kiểm tra nhanh hơn.', 'items' => []],
            ['heading' => '5. Thời gian phản hồi', 'content' => 'Yêu cầu được tiếp nhận trong giờ làm việc công bố trên website. Sự cố ảnh hưởng toàn bộ website được ưu tiên kiểm tra trước; các lỗi cục bộ hoặc yêu cầu hướng dẫn được sắp xếp theo mức độ ảnh hưởng và khối lượng xử lý.', 'items' => []],
            ['heading' => '6. Dịch vụ sau thời gian bảo hành', 'content' => 'Sau thời gian bảo hành, khách hàng có thể sử dụng gói Website Care, bảo trì theo năm hoặc yêu cầu hỗ trợ tính phí theo từng lần. Gói duy trì có thể bao gồm backup, cập nhật, giám sát, xử lý lỗi và hỗ trợ nội dung tùy cấu hình.', 'items' => []],
            ['heading' => '7. Sao lưu và dữ liệu', 'content' => 'Tần suất và thời gian lưu bản sao phụ thuộc gói hosting hoặc bảo trì. Khách hàng nên duy trì tài khoản quản trị, thông tin tên miền và bản sao dữ liệu quan trọng của riêng mình.', 'items' => []],
            ['heading' => '8. Trường hợp cần báo giá phát sinh', 'content' => 'Khi yêu cầu nằm ngoài phạm vi bảo hành, WebApp Bắc Ninh sẽ thông báo nguyên nhân, phương án xử lý, thời gian và chi phí dự kiến trước khi thực hiện.', 'items' => []],
        ]"
    />
@endsection
