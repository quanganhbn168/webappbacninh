@props([
    'need' => '',
    'submit' => 'Gửi yêu cầu tư vấn',
    'fields' => ['email', 'company', 'message'],
    'needOptions' => [],
    'id' => 'lead-'.\Illuminate\Support\Str::random(6),
])
<form {{ $attributes->merge(['class' => 'lead-form']) }} action="{{ route('leads.store') }}" method="post" data-lead-form novalidate>
    @csrf
    <div class="row g-3">
        <div class="col-sm-6">
            <label class="form-label" for="{{ $id }}-name">Họ và tên <span class="text-danger">*</span></label>
            <input class="form-control" id="{{ $id }}-name" name="name" autocomplete="name" minlength="2" maxlength="150" required placeholder="Họ và tên của anh/chị">
        </div>
        <div class="col-sm-6">
            <label class="form-label" for="{{ $id }}-phone">Số điện thoại <span class="text-danger">*</span></label>
            <input class="form-control" id="{{ $id }}-phone" name="phone" type="tel" inputmode="tel" autocomplete="tel" pattern="[0-9 +().-]{8,22}" maxlength="30" required placeholder="Số điện thoại liên hệ">
        </div>
        @if (in_array('email', $fields, true))
            <div class="col-sm-6">
                <label class="form-label" for="{{ $id }}-email">Email</label>
                <input class="form-control" id="{{ $id }}-email" name="email" type="email" autocomplete="email" maxlength="190" placeholder="Không bắt buộc">
            </div>
        @endif
        @if (in_array('company', $fields, true))
            <div class="col-sm-6">
                <label class="form-label" for="{{ $id }}-company">Doanh nghiệp / lĩnh vực</label>
                <input class="form-control" id="{{ $id }}-company" name="company" autocomplete="organization" maxlength="190">
            </div>
        @endif
        <div class="col-12">
            <label class="form-label" for="{{ $id }}-need">{{ $needOptions ? 'Dịch vụ bạn quan tâm' : 'Nhu cầu' }}</label>
            @if ($needOptions)
                @php($selected = $need ?: request()->query('service'))
                <select class="form-select" id="{{ $id }}-need" name="need" data-lead-need>
                    <option value="">Chọn dịch vụ quan tâm</option>
                    @foreach ($needOptions as $option)
                        <option @selected($selected === $option)>{{ $option }}</option>
                    @endforeach
                </select>
            @else
                <input class="form-control" id="{{ $id }}-need" name="need" maxlength="255" value="{{ $need }}" data-lead-need placeholder="Website, phần mềm, vận hành…">
            @endif
        </div>
        @if (in_array('message', $fields, true))
            <div class="col-12">
                <label class="form-label" for="{{ $id }}-message">Mô tả ngắn</label>
                <textarea class="form-control" id="{{ $id }}-message" name="message" rows="3" maxlength="5000" placeholder="Mục tiêu, số trang, chức năng cần có, thời gian mong muốn…"></textarea>
            </div>
        @endif
        <div class="col-12">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="{{ $id }}-consent" required>
                <label class="form-check-label small" for="{{ $id }}-consent">Tôi đồng ý cung cấp thông tin để được liên hệ tư vấn (<a href="{{ route('legal.privacy') }}">chính sách bảo mật</a>).</label>
            </div>
        </div>
        <div class="col-12">
            <button class="btn btn-primary" type="submit">{{ $submit }} <x-icon name="arrow-right" /></button>
        </div>
    </div>
    <div class="alert mt-3 mb-0" data-lead-status role="status" hidden></div>
</form>
