<?php

/**
 * First drafts of the industry pages (/mau-website/{slug}), keyed by industry slug. They fill
 * empty fields only and stay out of Google until the owner reviews them and turns the page on.
 */
$page = function (string $intro, string $meta, array $sections, array $faqs): array {
    $html = '';

    foreach ($sections as $heading => [$lead, $points]) {
        $html .= "<h2>{$heading}</h2><p>{$lead}</p><ul>".implode('', array_map(fn (string $point): string => "<li>{$point}</li>", $points)).'</ul>';
    }

    return [
        'description' => $intro,
        'meta_description' => $meta,
        'content' => $html,
        'faqs' => json_encode(array_map(fn (array $faq): array => ['q' => $faq[0], 'a' => $faq[1]], $faqs), JSON_UNESCAPED_UNICODE),
    ];
};

return [
    'san-xuat' => $page(
        'Mẫu website cho công ty sản xuất, cơ khí, linh kiện: trình bày năng lực nhà máy rõ ràng để khách mua hàng doanh nghiệp đánh giá và gửi yêu cầu báo giá.',
        'Mẫu website công ty sản xuất: giới thiệu năng lực nhà máy, sản phẩm, chứng chỉ và nhận yêu cầu báo giá. Xem mẫu và nhận tư vấn tùy chỉnh.',
        [
            'Website công ty sản xuất nên có những trang nào' => ['Người xem thường là bộ phận mua hàng. Họ cần biết xưởng làm được gì, làm đến quy mô nào và liên hệ với ai.', [
                'Giới thiệu công ty, lịch sử và cơ cấu nhà máy',
                'Năng lực sản xuất: máy móc, công suất, vật liệu gia công',
                'Danh mục sản phẩm, có thông số kỹ thuật và ảnh thật',
                'Chứng chỉ chất lượng, quy trình kiểm tra',
                'Dự án và ngành hàng đã phục vụ',
                'Liên hệ, bản đồ và form yêu cầu báo giá',
            ]],
            'Chức năng nên cân nhắc' => ['Chọn theo cách doanh nghiệp đang bán hàng, không cần làm tất cả ngay từ đầu.', [
                'Form báo giá cho phép đính kèm bản vẽ',
                'Tải hồ sơ năng lực dạng PDF',
                'Phiên bản tiếng Anh, tiếng Nhật, tiếng Hàn hoặc tiếng Trung cho khách nước ngoài',
                'Bài viết kỹ thuật để khách tìm thấy khi tra cứu',
            ]],
            'Nội dung cần chuẩn bị' => ['Ảnh và số liệu thật tạo niềm tin hơn ảnh minh họa.', [
                'Ảnh nhà xưởng, máy móc, sản phẩm',
                'Danh sách thiết bị và công suất',
                'Bản scan chứng chỉ còn hiệu lực',
                'Hồ sơ năng lực hiện có nếu đã làm',
            ]],
        ],
        [
            ['Chưa có ảnh nhà xưởng đẹp thì làm thế nào?', 'Có thể dựng website trước bằng ảnh hiện có, sau đó thay khi chụp được ảnh mới. Nên ưu tiên ảnh thật của xưởng, kể cả ảnh chụp đơn giản, hơn là ảnh tải trên mạng.'],
            ['Website có cần nhiều ngôn ngữ ngay không?', 'Nếu khách hàng chính là doanh nghiệp nước ngoài thì nên làm tiếng Anh từ đầu. Các ngôn ngữ khác có thể bổ sung sau khi nội dung tiếng Việt đã ổn định.'],
            ['Sản phẩm gia công theo bản vẽ thì trình bày ra sao?', 'Trình bày theo năng lực gia công: loại vật liệu, công nghệ, dung sai, kích thước tối đa và vài sản phẩm tiêu biểu được phép công bố.'],
        ],
    ),
    'thuong-mai' => $page(
        'Mẫu website bán hàng cho cửa hàng và doanh nghiệp thương mại: khách xem sản phẩm, đặt hàng và liên hệ ngay trên điện thoại.',
        'Mẫu website bán hàng: danh mục sản phẩm, giỏ hàng, đặt hàng trên điện thoại và quản lý đơn. Xem các mẫu và nhận tư vấn tùy chỉnh.',
        [
            'Website bán hàng nên có những trang nào' => ['Mục tiêu là để khách tìm được sản phẩm và đặt mua với ít bước nhất.', [
                'Trang chủ nêu sản phẩm chính và chương trình đang có',
                'Danh mục sản phẩm có bộ lọc và tìm kiếm',
                'Trang sản phẩm có ảnh, giá, mô tả và tình trạng hàng',
                'Giỏ hàng, đặt hàng và xác nhận đơn',
                'Chính sách giao hàng, đổi trả, bảo hành, thanh toán',
                'Liên hệ và địa chỉ cửa hàng',
            ]],
            'Chức năng nên cân nhắc' => ['Bắt đầu từ những việc đang làm thủ công nhiều nhất.', [
                'Quản lý đơn hàng và khách hàng trong trang quản trị',
                'Thanh toán chuyển khoản bằng mã QR',
                'Nút gọi và nhắn Zalo luôn hiển thị trên điện thoại',
                'Gắn đo lường quảng cáo để biết đơn đến từ đâu',
            ]],
            'Nội dung cần chuẩn bị' => ['Thông tin sản phẩm đầy đủ giúp khách quyết định mà không phải hỏi lại.', [
                'Danh sách sản phẩm kèm giá và phân loại',
                'Ảnh sản phẩm nền gọn, rõ chi tiết',
                'Chính sách bán hàng đang áp dụng',
                'Thông tin tài khoản nhận thanh toán',
            ]],
        ],
        [
            ['Đang bán trên sàn và mạng xã hội thì có cần website không?', 'Website là kênh doanh nghiệp tự quản lý: giữ được thông tin khách, trình bày theo ý mình và là nơi dẫn khách từ quảng cáo, tìm kiếm về. Nó bổ sung chứ không thay thế các kênh đang bán.'],
            ['Ít sản phẩm thì nên làm website bán hàng hay landing page?', 'Nếu chỉ bán một vài sản phẩm và chủ yếu chạy quảng cáo, landing page thường gọn hơn. Khi cần danh mục, giỏ hàng và quản lý đơn thì chọn website bán hàng.'],
            ['Tự đăng sản phẩm mới được không?', 'Được. Sản phẩm, giá và hình ảnh được cập nhật trong trang quản trị, có hướng dẫn thao tác khi bàn giao.'],
        ],
    ),
    'du-lich' => $page(
        'Mẫu website du lịch cho công ty lữ hành và đơn vị tổ chức tour: giới thiệu lịch trình, giá và nhận yêu cầu đặt chỗ.',
        'Mẫu website du lịch, tour: lịch trình từng ngày, giá, ngày khởi hành và form đặt chỗ. Xem mẫu và nhận tư vấn tùy chỉnh cho công ty lữ hành.',
        [
            'Website du lịch nên có những trang nào' => ['Khách so sánh nhiều đơn vị, nên lịch trình và điều kiện cần rõ ràng ngay trên trang.', [
                'Danh sách tour theo điểm đến và thời gian',
                'Trang tour có lịch trình từng ngày, giá bao gồm và không bao gồm',
                'Ngày khởi hành và điều kiện hủy, đổi',
                'Cẩm nang điểm đến',
                'Hình ảnh các đoàn đã đi',
                'Liên hệ và form đặt tour',
            ]],
            'Chức năng nên cân nhắc' => ['Giúp khách hỏi và đặt nhanh trong mùa cao điểm.', [
                'Lọc tour theo điểm đến, mức giá, số ngày',
                'Form đặt tour chọn ngày và số người',
                'Nút gọi, nhắn Zalo trên từng tour',
                'Đa ngôn ngữ nếu đón khách quốc tế',
            ]],
            'Nội dung cần chuẩn bị' => ['Ảnh chuyến đi thật là điểm khác biệt so với đơn vị khác.', [
                'Lịch trình và bảng giá hiện hành',
                'Ảnh điểm đến, khách sạn, phương tiện',
                'Giấy phép kinh doanh lữ hành nếu có',
                'Điều khoản đặt cọc và hoàn tiền',
            ]],
        ],
        [
            ['Giá tour thay đổi theo mùa thì quản lý ra sao?', 'Giá và ngày khởi hành được sửa trong trang quản trị. Nên ghi rõ thời điểm áp dụng để khách không hiểu nhầm.'],
            ['Có thể nhận thanh toán đặt cọc trên website không?', 'Có thể bổ sung thanh toán chuyển khoản bằng mã QR hoặc cổng thanh toán. Phạm vi cụ thể được chốt khi khảo sát.'],
            ['Tour thiết kế riêng cho đoàn thì trình bày thế nào?', 'Nên có một trang giới thiệu dịch vụ đoàn riêng, kèm form để khách mô tả số người, thời gian, ngân sách và nhu cầu.'],
        ],
    ),
    'giao-duc' => $page(
        'Mẫu website cho trường học, trung tâm đào tạo: giới thiệu khóa học, giáo viên, lịch khai giảng và nhận đăng ký học.',
        'Mẫu website giáo dục, trung tâm đào tạo: khóa học, giáo viên, lịch khai giảng và form đăng ký học. Xem mẫu và nhận tư vấn tùy chỉnh.',
        [
            'Website giáo dục nên có những trang nào' => ['Phụ huynh và học viên cần biết học gì, ai dạy, khi nào học và học phí tính ra sao.', [
                'Giới thiệu trường, trung tâm và phương pháp dạy',
                'Danh sách khóa học theo độ tuổi hoặc trình độ',
                'Đội ngũ giáo viên',
                'Lịch khai giảng và học phí',
                'Hoạt động, hình ảnh lớp học',
                'Đăng ký học thử, tư vấn',
            ]],
            'Chức năng nên cân nhắc' => ['Ưu tiên việc tiếp nhận và chăm sóc đăng ký.', [
                'Form đăng ký theo khóa học',
                'Danh sách đăng ký trong trang quản trị',
                'Tin tức và thông báo cho phụ huynh',
                'Gắn đo lường để biết đăng ký đến từ đâu',
            ]],
            'Nội dung cần chuẩn bị' => ['Chỉ dùng ảnh học viên khi đã được phụ huynh hoặc học viên đồng ý.', [
                'Chương trình học của từng khóa',
                'Thông tin và ảnh giáo viên',
                'Ảnh cơ sở vật chất',
                'Lịch học và chính sách học phí',
            ]],
        ],
        [
            ['Có làm học trực tuyến trên website được không?', 'Website giới thiệu và tuyển sinh khác với hệ thống học trực tuyến. Nếu cần bài giảng, bài tập, theo dõi tiến độ thì đó là phần phát triển riêng, cần khảo sát trước.'],
            ['Học phí chưa muốn công khai thì sao?', 'Có thể để khách đăng ký tư vấn thay vì hiển thị giá. Nếu công khai được khoảng học phí, người xem sẽ dễ quyết định hơn.'],
            ['Nhiều cơ sở thì trình bày thế nào?', 'Mỗi cơ sở nên có địa chỉ, số điện thoại, bản đồ và lịch khai giảng riêng để người học chọn nơi gần nhất.'],
        ],
    ),
    'noi-that' => $page(
        'Mẫu website nội thất và xây dựng: trưng bày công trình đã làm, quy trình thi công và nhận yêu cầu khảo sát.',
        'Mẫu website nội thất, xây dựng: thư viện công trình, hạng mục thi công, quy trình và đăng ký khảo sát. Xem mẫu và nhận tư vấn tùy chỉnh.',
        [
            'Website nội thất, xây dựng nên có những trang nào' => ['Khách chọn đơn vị thi công chủ yếu qua công trình thực tế.', [
                'Công trình đã thực hiện, phân theo loại nhà và phong cách',
                'Dịch vụ thiết kế, thi công, cung cấp vật tư',
                'Quy trình từ khảo sát đến bàn giao',
                'Vật liệu sử dụng và chính sách bảo hành',
                'Giới thiệu đội ngũ, xưởng sản xuất',
                'Đăng ký khảo sát, báo giá',
            ]],
            'Chức năng nên cân nhắc' => ['Làm cho phần hình ảnh dễ xem, nhất là trên điện thoại.', [
                'Thư viện ảnh trượt cho từng công trình',
                'Lọc công trình theo hạng mục',
                'Form khảo sát ghi diện tích, địa điểm, ngân sách',
                'Bài viết kinh nghiệm chọn vật liệu',
            ]],
            'Nội dung cần chuẩn bị' => ['Ảnh trước và sau thi công rất thuyết phục.', [
                'Ảnh công trình đã bàn giao',
                'Thông tin từng công trình được chủ nhà cho phép công bố',
                'Danh sách hạng mục và vật liệu',
                'Chính sách bảo hành',
            ]],
        ],
        [
            ['Có nên đưa đơn giá lên website không?', 'Nếu có khung đơn giá tương đối ổn định, công khai kèm điều kiện áp dụng giúp lọc khách phù hợp. Nếu giá phụ thuộc nhiều vào hiện trạng thì nên mời khảo sát.'],
            ['Chỉ có ảnh phối cảnh 3D thì dùng được không?', 'Được, nhưng nên ghi rõ là phối cảnh thiết kế và bổ sung ảnh thi công thực tế khi có.'],
            ['Tự thêm công trình mới được không?', 'Được. Công trình và hình ảnh được đăng trong trang quản trị.'],
        ],
    ),
    'nha-hang' => $page(
        'Mẫu website nhà hàng, quán ăn: thực đơn, không gian, chỉ đường và đặt bàn ngay trên điện thoại.',
        'Mẫu website nhà hàng, quán ăn: thực đơn, hình ảnh không gian, bản đồ và đặt bàn trên điện thoại. Xem mẫu và nhận tư vấn tùy chỉnh.',
        [
            'Website nhà hàng nên có những trang nào' => ['Phần lớn khách xem trên điện thoại, đang muốn biết ăn gì, ở đâu và còn bàn không.', [
                'Thực đơn theo nhóm món, kèm giá',
                'Không gian, phòng riêng, sức chứa',
                'Set tiệc, liên hoan, sự kiện',
                'Địa chỉ, bản đồ, giờ mở cửa',
                'Đặt bàn',
                'Tin ưu đãi',
            ]],
            'Chức năng nên cân nhắc' => ['Giảm số lần khách phải gọi hỏi những thông tin cơ bản.', [
                'Form đặt bàn chọn ngày, giờ, số người',
                'Nút gọi và chỉ đường luôn hiển thị',
                'Cập nhật thực đơn trong trang quản trị',
                'Trang riêng cho từng cơ sở nếu có nhiều địa điểm',
            ]],
            'Nội dung cần chuẩn bị' => ['Ảnh món ăn chụp thật, đủ sáng.', [
                'Thực đơn và giá hiện hành',
                'Ảnh món chủ đạo và không gian',
                'Giờ mở cửa, chỗ để xe',
                'Chính sách đặt cọc cho tiệc',
            ]],
        ],
        [
            ['Thực đơn thay đổi thường xuyên có bất tiện không?', 'Món và giá được sửa trực tiếp trong trang quản trị, không cần làm lại ảnh thực đơn.'],
            ['Có nhận đặt món giao hàng được không?', 'Có thể bổ sung giỏ hàng và đặt món. Cần thống nhất khu vực giao, phí và cách nhận đơn trước khi làm.'],
            ['Khách đặt bàn thì quán nhận thông tin ở đâu?', 'Thông tin được lưu trong trang quản trị và có thể gửi thông báo đến kênh quán đang dùng, theo phạm vi triển khai.'],
        ],
    ),
    'spa' => $page(
        'Mẫu website spa, thẩm mỹ, làm đẹp: giới thiệu dịch vụ, bảng giá, không gian và đặt lịch hẹn.',
        'Mẫu website spa, làm đẹp: dịch vụ, bảng giá, không gian, đội ngũ và đặt lịch hẹn trên điện thoại. Xem mẫu và nhận tư vấn tùy chỉnh.',
        [
            'Website spa nên có những trang nào' => ['Khách quan tâm dịch vụ nào phù hợp, giá bao nhiêu và nơi đó có đáng tin không.', [
                'Dịch vụ theo nhu cầu, mỗi dịch vụ có quy trình và thời lượng',
                'Bảng giá và gói liệu trình',
                'Không gian, thiết bị',
                'Đội ngũ và chuyên môn',
                'Đặt lịch hẹn',
                'Kiến thức chăm sóc da, cơ thể',
            ]],
            'Chức năng nên cân nhắc' => ['Giúp khách đặt lịch ngoài giờ làm việc.', [
                'Đặt lịch chọn dịch vụ, ngày và giờ',
                'Quản lý lịch hẹn trong trang quản trị',
                'Nút gọi, nhắn Zalo, chỉ đường',
                'Trang ưu đãi theo đợt',
            ]],
            'Nội dung cần chuẩn bị' => ['Không cam kết hiệu quả quá mức; ảnh khách hàng chỉ đăng khi được đồng ý.', [
                'Danh sách dịch vụ và giá',
                'Ảnh không gian, thiết bị thật',
                'Chứng chỉ, giấy phép liên quan',
                'Chính sách liệu trình và đổi lịch',
            ]],
        ],
        [
            ['Có đặt lịch theo từng kỹ thuật viên được không?', 'Được nếu cần, nhưng cách xếp ca và trùng lịch phải được thống nhất trước. Nhiều cơ sở bắt đầu bằng đặt lịch theo giờ rồi mở rộng sau.'],
            ['Dịch vụ thẩm mỹ cần lưu ý gì khi đăng nội dung?', 'Nội dung quảng cáo dịch vụ liên quan đến y tế, thẩm mỹ chịu quy định riêng. Cơ sở cần có giấy phép phù hợp và tự chịu trách nhiệm về thông tin chuyên môn.'],
            ['Tự đổi giá và ưu đãi được không?', 'Được, trong trang quản trị.'],
        ],
    ),
    'may-moc-thiet-bi' => $page(
        'Mẫu website cho đơn vị bán, cho thuê và bảo trì máy móc, thiết bị: danh mục có thông số rõ ràng và nhận yêu cầu báo giá.',
        'Mẫu website máy móc, thiết bị: danh mục sản phẩm, thông số kỹ thuật, tài liệu và yêu cầu báo giá. Xem mẫu và nhận tư vấn tùy chỉnh.',
        [
            'Website máy móc, thiết bị nên có những trang nào' => ['Khách thường biết khá rõ mình cần thông số nào, nên dữ liệu sản phẩm phải đầy đủ.', [
                'Danh mục theo loại máy, hãng, công suất',
                'Trang sản phẩm có thông số, ứng dụng, tài liệu đi kèm',
                'Giải pháp theo ngành sử dụng',
                'Dịch vụ lắp đặt, bảo trì, phụ tùng',
                'Dự án đã cung cấp',
                'Yêu cầu báo giá, hỗ trợ kỹ thuật',
            ]],
            'Chức năng nên cân nhắc' => ['Rút ngắn đoạn từ xem thông số đến gửi yêu cầu.', [
                'Lọc sản phẩm theo thông số chính',
                'Tải catalogue, tài liệu PDF',
                'Báo giá nhiều sản phẩm trong một lần gửi',
                'Form báo hỏng, đặt lịch bảo trì',
            ]],
            'Nội dung cần chuẩn bị' => ['Xin phép hãng trước khi dùng hình ảnh, tài liệu của họ.', [
                'Danh sách model và thông số',
                'Ảnh thiết bị, ảnh lắp đặt thực tế',
                'Catalogue, hướng dẫn sử dụng',
                'Chính sách bảo hành, bảo trì',
            ]],
        ],
        [
            ['Không muốn công khai giá máy thì làm thế nào?', 'Để nút yêu cầu báo giá trên từng sản phẩm. Khách gửi kèm model và nhu cầu, bộ phận kinh doanh phản hồi.'],
            ['Có rất nhiều model thì nhập liệu ra sao?', 'Nên thống nhất bộ thông số chung cho từng nhóm máy trước, sau đó nhập theo đợt. Nếu đã có file bảng tính, có thể trao đổi phương án nhập dữ liệu.'],
            ['Website có cần tiếng Anh không?', 'Cần khi khách hàng là doanh nghiệp có vốn nước ngoài hoặc khi xuất khẩu. Có thể bổ sung sau.'],
        ],
    ),
    'dien-gia-dung' => $page(
        'Mẫu website, landing page bán điện máy, thiết bị gia dụng: làm rõ công dụng sản phẩm, ưu đãi và nhận đơn nhanh.',
        'Mẫu website, landing page điện gia dụng: giới thiệu tính năng, thông số, ưu đãi và nhận đơn hàng. Xem mẫu và nhận tư vấn tùy chỉnh.',
        [
            'Trang bán đồ điện gia dụng nên có những gì' => ['Khách cần hiểu sản phẩm giải quyết việc gì trong nhà trước khi xem thông số.', [
                'Công dụng chính, trình bày bằng tình huống sử dụng',
                'Thông số, công suất, diện tích phù hợp',
                'Ảnh và video sản phẩm thật',
                'Giá, quà tặng, thời hạn ưu đãi',
                'Bảo hành, giao hàng, đổi trả',
                'Form đặt mua ngay trên trang',
            ]],
            'Chọn website hay landing page' => ['Tùy số lượng sản phẩm và cách bán.', [
                'Một hoặc vài sản phẩm chạy quảng cáo: landing page',
                'Nhiều dòng sản phẩm, cần giỏ hàng: website bán hàng',
                'Cả hai đều nên gắn đo lường để biết quảng cáo nào ra đơn',
            ]],
            'Nội dung cần chuẩn bị' => ['Chỉ công bố tính năng, chứng nhận đúng với sản phẩm.', [
                'Thông số từ nhà sản xuất',
                'Ảnh, video chụp thật',
                'Giấy chứng nhận, kiểm định nếu quảng cáo công dụng',
                'Chính sách bảo hành',
            ]],
        ],
        [
            ['Đổi giá và quà tặng theo chương trình được không?', 'Được. Giá, quà và nội dung ưu đãi được sửa trong trang quản trị.'],
            ['Đơn hàng từ trang được gửi về đâu?', 'Đơn được lưu trong trang quản trị và có thể báo về kênh đang dùng, theo phạm vi triển khai.'],
            ['Có gắn mã đo quảng cáo Facebook, Google được không?', 'Được. Mỗi landing page có thể gắn mã theo dõi riêng để đo từng chiến dịch.'],
        ],
    ),
];
