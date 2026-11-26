@extends('layouts.basic')

@section('title', 'Demo '.$theme->name)

@push('head')
    @vite('resources/js/themes/preview.js')
@endpush

@section('content')
    <div class="demo-preview" data-demo-preview data-theme-code="{{ $theme->code }}">
        <header class="demo-preview__bar">
            <div class="demo-preview__title">
                <a class="btn btn-outline-secondary btn-sm" href="{{ $theme->url }}" aria-label="Quay lại trang mẫu"><x-icon name="arrow-left" /></a>
                <div>
                    <h1>{{ $theme->name }}</h1>
                    <span>{{ $theme->code }} ·
                        @if ($theme->old_price)<del>{{ money($theme->old_price) }}</del> <b class="text-danger">{{ $theme->price_label }}</b>
                        @elseif ($theme->effective_price)Giá từ {{ $theme->price_label }}
                        @else{{ $theme->price_label }}@endif
                    </span>
                </div>
            </div>
            <div class="btn-group demo-preview__devices" role="group" aria-label="Xem trên thiết bị">
                @foreach (['desktop' => ['monitor', 'Máy tính'], 'tablet' => ['tablet', 'Tablet'], 'mobile' => ['smartphone', 'Mobile']] as $device => [$icon, $label])
                    <button type="button" @class(['btn btn-outline-primary btn-sm', 'active' => $device === 'desktop']) data-device="{{ $device }}" aria-pressed="{{ $device === 'desktop' ? 'true' : 'false' }}"><x-icon :name="$icon" /> <span>{{ $label }}</span></button>
                @endforeach
            </div>
            <div class="demo-preview__actions">
                <a class="btn btn-link btn-sm" href="{{ $theme->demo_source }}" target="_blank" rel="noopener">Mở tab mới <x-icon name="external-link" /></a>
                <a class="btn btn-primary btn-sm" href="{{ $theme->url }}#chon-mau" data-ga-event="select_template">Chọn mẫu này</a>
            </div>
        </header>
        <div class="demo-preview__stage" data-stage>
            <div class="demo-preview__screen" data-screen>
                <iframe src="{{ $theme->demo_source }}" title="Demo {{ $theme->name }}" data-frame></iframe>
            </div>
        </div>
    </div>
@endsection
