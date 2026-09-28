@extends('layouts.site')

@section('content')
    <x-legal-document
        title="Quy trình thanh toán và triển khai"
        eyebrow="THÔNG TIN VÀ CHÍNH SÁCH"
        summary="Quy trình thanh toán được chia theo từng giai đoạn nhằm bảo đảm hai bên cùng kiểm soát phạm vi, tiến độ và chất lượng bàn giao. Tỷ lệ cụ thể có thể thay đổi theo quy mô dự án và sẽ được ghi trong báo giá."
        notice="Khách hàng chỉ thanh toán theo thông tin tài khoản được xác nhận trong báo giá, hợp đồng, email hoặc kênh liên hệ chính thức của WebApp Bắc Ninh."
        icon="banknote"
        updated="2026-07-11"
        :sections="[
            ['heading' => '1. Tiếp nhận và làm rõ nhu cầu', 'content' => 'Hai bên trao đổi về mục tiêu, nhóm trang, tính năng, dữ liệu hiện có, thời gian mong muốn và phạm vi hỗ trợ sau bàn giao.', 'items' => []],
            ['heading' => '2. Gửi báo giá và phạm vi công việc', 'content' => 'Báo giá nêu các hạng mục chính, sản phẩm bàn giao, số lần điều chỉnh, tiến độ dự kiến, trách nhiệm cung cấp dữ liệu và điều kiện thanh toán.', 'items' => []],
            ['heading' => '3. Xác nhận triển khai và đặt cọc', 'content' => 'Dự án bắt đầu sau khi hai bên xác nhận phạm vi và WebApp Bắc Ninh nhận được khoản đặt cọc theo báo giá. Khoản đặt cọc được sử dụng để bố trí nguồn lực, thiết kế và triển khai phần việc ban đầu.', 'items' => []],
            ['heading' => '4. Các giai đoạn thanh toán tham khảo', 'content' => '', 'items' => ['Giai đoạn 1: đặt cọc khi xác nhận triển khai.', 'Giai đoạn 2: thanh toán tiếp theo khi hoàn thành giao diện hoặc bản chạy thử theo mốc đã thống nhất.', 'Giai đoạn 3: thanh toán phần còn lại trước hoặc tại thời điểm bàn giao chính thức.', 'Dự án nhỏ có thể áp dụng hai đợt; dự án lớn hoặc phần mềm riêng có thể chia nhiều mốc hơn.']],
            ['heading' => '5. Phương thức thanh toán', 'content' => '', 'items' => ['Chuyển khoản ngân hàng theo thông tin được xác nhận chính thức.', 'Tiền mặt khi có phiếu thu hoặc xác nhận tương ứng.', 'Phương thức khác được hai bên thống nhất bằng văn bản.']],
            ['heading' => '6. Nghiệm thu và bàn giao', 'content' => 'Khách hàng kiểm tra website theo phạm vi đã chốt. Sau khi các lỗi thuộc phạm vi được xử lý và khoản thanh toán đến hạn hoàn tất, WebApp Bắc Ninh thực hiện bàn giao tài khoản, mã nguồn hoặc thông tin vận hành theo thỏa thuận.', 'items' => []],
            ['heading' => '7. Chi phí phát sinh', 'content' => 'Yêu cầu ngoài báo giá như thêm chức năng, tăng số trang, nhập thêm dữ liệu, tích hợp mới hoặc thay đổi thiết kế lớn sẽ được xác nhận và báo chi phí trước khi thực hiện.', 'items' => []],
            ['heading' => '8. Tạm dừng hoặc hủy dự án', 'content' => 'Khi dự án tạm dừng hoặc hủy, hai bên đối chiếu khối lượng đã thực hiện, chi phí hạ tầng đã mua và nghĩa vụ thanh toán theo thỏa thuận. Khoản đặt cọc có thể được sử dụng để bù đắp phần công việc và chi phí đã phát sinh.', 'items' => []],
            ['heading' => '9. Hóa đơn và chứng từ', 'content' => 'Nhu cầu về hóa đơn, chứng từ hoặc thông tin đơn vị thanh toán cần được thông báo trước khi xác nhận báo giá để hai bên thống nhất phương án phù hợp.', 'items' => []],
        ]"
    />
@endsection
