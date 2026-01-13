<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines - Vietnamese
    |--------------------------------------------------------------------------
    */

    'accepted' => ':attribute phải được chấp nhận.',
    'accepted_if' => ':attribute phải được chấp nhận khi :other là :value.',
    'active_url' => ':attribute không phải là URL hợp lệ.',
    'after' => ':attribute phải là ngày sau :date.',
    'after_or_equal' => ':attribute phải là ngày sau hoặc bằng :date.',
    'alpha' => ':attribute chỉ được chứa các chữ cái.',
    'alpha_dash' => ':attribute chỉ được chứa chữ cái, số, dấu gạch ngang và gạch dưới.',
    'alpha_num' => ':attribute chỉ được chứa chữ cái và số.',
    'array' => ':attribute phải là một mảng.',
    'before' => ':attribute phải là ngày trước :date.',
    'before_or_equal' => ':attribute phải là ngày trước hoặc bằng :date.',
    'between' => [
        'array' => ':attribute phải có từ :min đến :max phần tử.',
        'file' => ':attribute phải từ :min đến :max KB.',
        'numeric' => ':attribute phải từ :min đến :max.',
        'string' => ':attribute phải từ :min đến :max ký tự.',
    ],
    'boolean' => ':attribute phải là true hoặc false.',
    'confirmed' => 'Xác nhận :attribute không khớp.',
    'current_password' => 'Mật khẩu không đúng.',
    'date' => ':attribute không phải là ngày hợp lệ.',
    'date_equals' => ':attribute phải là ngày bằng :date.',
    'date_format' => ':attribute không đúng định dạng :format.',
    'declined' => ':attribute phải bị từ chối.',
    'declined_if' => ':attribute phải bị từ chối khi :other là :value.',
    'different' => ':attribute và :other phải khác nhau.',
    'digits' => ':attribute phải có :digits chữ số.',
    'digits_between' => ':attribute phải từ :min đến :max chữ số.',
    'dimensions' => ':attribute có kích thước ảnh không hợp lệ.',
    'distinct' => ':attribute có giá trị trùng lặp.',
    'email' => ':attribute phải là địa chỉ email hợp lệ.',
    'ends_with' => ':attribute phải kết thúc bằng một trong các giá trị: :values.',
    'enum' => ':attribute được chọn không hợp lệ.',
    'exists' => ':attribute được chọn không hợp lệ.',
    'file' => ':attribute phải là một file.',
    'filled' => ':attribute không được để trống.',
    'gt' => [
        'array' => ':attribute phải có nhiều hơn :value phần tử.',
        'file' => ':attribute phải lớn hơn :value KB.',
        'numeric' => ':attribute phải lớn hơn :value.',
        'string' => ':attribute phải nhiều hơn :value ký tự.',
    ],
    'gte' => [
        'array' => ':attribute phải có ít nhất :value phần tử.',
        'file' => ':attribute phải lớn hơn hoặc bằng :value KB.',
        'numeric' => ':attribute phải lớn hơn hoặc bằng :value.',
        'string' => ':attribute phải có ít nhất :value ký tự.',
    ],
    'image' => ':attribute phải là hình ảnh.',
    'in' => ':attribute được chọn không hợp lệ.',
    'in_array' => ':attribute không tồn tại trong :other.',
    'integer' => ':attribute phải là số nguyên.',
    'ip' => ':attribute phải là địa chỉ IP hợp lệ.',
    'ipv4' => ':attribute phải là địa chỉ IPv4 hợp lệ.',
    'ipv6' => ':attribute phải là địa chỉ IPv6 hợp lệ.',
    'json' => ':attribute phải là chuỗi JSON hợp lệ.',
    'lt' => [
        'array' => ':attribute phải có ít hơn :value phần tử.',
        'file' => ':attribute phải nhỏ hơn :value KB.',
        'numeric' => ':attribute phải nhỏ hơn :value.',
        'string' => ':attribute phải ít hơn :value ký tự.',
    ],
    'lte' => [
        'array' => ':attribute không được có nhiều hơn :value phần tử.',
        'file' => ':attribute không được lớn hơn :value KB.',
        'numeric' => ':attribute không được lớn hơn :value.',
        'string' => ':attribute không được nhiều hơn :value ký tự.',
    ],
    'mac_address' => ':attribute phải là địa chỉ MAC hợp lệ.',
    'max' => [
        'array' => ':attribute không được có nhiều hơn :max phần tử.',
        'file' => ':attribute không được lớn hơn :max KB.',
        'numeric' => ':attribute không được lớn hơn :max.',
        'string' => ':attribute không được nhiều hơn :max ký tự.',
    ],
    'mimes' => ':attribute phải là file có định dạng: :values.',
    'mimetypes' => ':attribute phải là file có định dạng: :values.',
    'min' => [
        'array' => ':attribute phải có ít nhất :min phần tử.',
        'file' => ':attribute phải có ít nhất :min KB.',
        'numeric' => ':attribute phải lớn hơn hoặc bằng :min.',
        'string' => ':attribute phải có ít nhất :min ký tự.',
    ],
    'multiple_of' => ':attribute phải là bội số của :value.',
    'not_in' => ':attribute được chọn không hợp lệ.',
    'not_regex' => 'Định dạng :attribute không hợp lệ.',
    'numeric' => ':attribute phải là số.',
    'password' => [
        'letters' => ':attribute phải chứa ít nhất một chữ cái.',
        'mixed' => ':attribute phải chứa ít nhất một chữ hoa và một chữ thường.',
        'numbers' => ':attribute phải chứa ít nhất một chữ số.',
        'symbols' => ':attribute phải chứa ít nhất một ký tự đặc biệt.',
        'uncompromised' => ':attribute này đã xuất hiện trong danh sách bị rò rỉ. Vui lòng chọn :attribute khác.',
    ],
    'present' => ':attribute phải hiện diện.',
    'prohibited' => ':attribute bị cấm.',
    'prohibited_if' => ':attribute bị cấm khi :other là :value.',
    'prohibited_unless' => ':attribute bị cấm trừ khi :other nằm trong :values.',
    'prohibits' => ':attribute cấm :other xuất hiện.',
    'regex' => 'Định dạng :attribute không hợp lệ.',
    'required' => 'Vui lòng nhập :attribute.',
    'required_array_keys' => ':attribute phải chứa các mục: :values.',
    'required_if' => ':attribute là bắt buộc khi :other là :value.',
    'required_unless' => ':attribute là bắt buộc trừ khi :other nằm trong :values.',
    'required_with' => ':attribute là bắt buộc khi có :values.',
    'required_with_all' => ':attribute là bắt buộc khi có :values.',
    'required_without' => ':attribute là bắt buộc khi không có :values.',
    'required_without_all' => ':attribute là bắt buộc khi không có :values nào.',
    'same' => ':attribute và :other phải giống nhau.',
    'size' => [
        'array' => ':attribute phải chứa :size phần tử.',
        'file' => ':attribute phải có kích thước :size KB.',
        'numeric' => ':attribute phải bằng :size.',
        'string' => ':attribute phải có :size ký tự.',
    ],
    'starts_with' => ':attribute phải bắt đầu bằng một trong các giá trị: :values.',
    'string' => ':attribute phải là chuỗi ký tự.',
    'timezone' => ':attribute phải là múi giờ hợp lệ.',
    'unique' => ':attribute đã tồn tại.',
    'uploaded' => ':attribute tải lên thất bại.',
    'url' => ':attribute phải là URL hợp lệ.',
    'uuid' => ':attribute phải là UUID hợp lệ.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    */

    'custom' => [
        'slug' => [
            'unique' => 'Đường dẫn này đã được sử dụng, vui lòng chọn tên khác.',
        ],
        'meta_title' => [
            'max' => 'SEO Title không nên vượt quá :max ký tự để tối ưu SEO.',
        ],
        'meta_description' => [
            'max' => 'SEO Description không nên vượt quá :max ký tự để tối ưu SEO.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes - Vietnamese
    |--------------------------------------------------------------------------
    */

    'attributes' => [
        // Common
        'name' => 'tên',
        'email' => 'email',
        'password' => 'mật khẩu',
        'password_confirmation' => 'xác nhận mật khẩu',
        'phone' => 'số điện thoại',
        'address' => 'địa chỉ',
        'description' => 'mô tả',
        'image' => 'hình ảnh',
        'file' => 'tập tin',
        'url' => 'đường dẫn',
        'title' => 'tiêu đề',
        'content' => 'nội dung',
        'slug' => 'đường dẫn SEO',
        
        // Post/Blog
        'category_id' => 'danh mục',
        'summary' => 'tóm tắt',
        'featured_image' => 'ảnh đại diện',
        'og_image' => 'ảnh chia sẻ',
        'meta_title' => 'SEO Title',
        'meta_description' => 'SEO Description',
        'meta_keywords' => 'từ khóa SEO',
        'is_published' => 'trạng thái xuất bản',
        'published_at' => 'ngày xuất bản',
        'tags' => 'thẻ tags',
        
        // User
        'first_name' => 'họ',
        'last_name' => 'tên',
        'username' => 'tên đăng nhập',
        'avatar' => 'ảnh đại diện',
        'role' => 'vai trò',
        
        // Product (for future)
        'price' => 'giá',
        'sku' => 'mã sản phẩm',
        'quantity' => 'số lượng',
        'stock' => 'tồn kho',
        
        // Settings
        'site_name' => 'tên website',
        'site_logo' => 'logo website',
        'contact_email' => 'email liên hệ',
        'contact_phone' => 'số điện thoại liên hệ',
    ],

];
