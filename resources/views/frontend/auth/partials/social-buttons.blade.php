@php($providers = \App\Enums\SocialProvider::configured())
@if ($providers !== [])
    <div class="position-relative mb-4 text-center">
        <hr class="text-secondary opacity-25">
        <span class="position-absolute top-50 start-50 translate-middle bg-body px-2 small text-muted">{{ $label }}</span>
    </div>

    <div class="d-flex gap-2 mb-3">
        @foreach ($providers as $provider)
            @php($style = match ($provider) {
                \App\Enums\SocialProvider::Google => ['btn-outline-danger', 'brand-google', ''],
                \App\Enums\SocialProvider::Facebook => ['btn-outline-primary', 'brand-facebook', '--bs-btn-color:#1877F2;--bs-btn-border-color:#1877F2;--bs-btn-hover-bg:#1877F2;--bs-btn-hover-border-color:#1877F2'],
                \App\Enums\SocialProvider::Zalo => ['btn-outline-primary', 'brand-zalo', '--bs-btn-color:#0068FF;--bs-btn-border-color:#0068FF;--bs-btn-hover-bg:#0068FF;--bs-btn-hover-border-color:#0068FF'],
            })
            <a href="{{ route('social.login', $provider->value) }}" class="btn {{ $style[0] }} flex-fill fw-bold" @if ($style[2]) style="{{ $style[2] }}" @endif>
                <x-icon :name="$style[1]" class="me-2" /> {{ $provider->label() }}
            </a>
        @endforeach
    </div>
@endif
