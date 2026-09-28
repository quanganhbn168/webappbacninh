{{-- Add-on modules; each opens the consultation form. --}}
<div class="module-list">
    @foreach ([['shopping-cart', 'Bán hàng online'], ['boxes', 'Quản lý kho'], ['headset', 'Chăm sóc khách hàng'], ['receipt', 'Kế toán - Hóa đơn'], ['calendar-check', 'Đặt lịch - Booking'], ['plug', 'Tích hợp API']] as [$icon, $label])
        <button class="module-pill" type="button" data-consult="Module: {{ $label }}"><x-icon :name="$icon" /> {{ $label }}</button>
    @endforeach
</div>
