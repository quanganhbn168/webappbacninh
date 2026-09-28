{{-- Policy / terms page: hero, table of contents and numbered sections. --}}
@props(['title', 'eyebrow' => 'Thông tin và chính sách', 'summary' => null, 'notice' => null, 'icon' => 'file-text', 'updated' => null, 'sections' => []])
<x-hero :eyebrow="$eyebrow" :title="$title" :lead="$summary" class="page-hero--compact">
    <x-slot:meta>
        <div class="page-hero__meta">
            @if ($updated)
                <span><x-icon name="calendar-check" class="icon-sm" /> Cập nhật lần cuối: {{ \Illuminate\Support\Carbon::parse($updated)->format('d/m/Y') }}</span>
            @endif
            <span><x-icon name="building-2" class="icon-sm" /> {{ site_config('name') }}</span>
        </div>
    </x-slot:meta>
    <x-slot:aside>
        <div class="legal-hero-icon d-none d-lg-flex"><x-icon :name="$icon" /></div>
    </x-slot:aside>
</x-hero>

<section class="section section--soft">
    <div class="container">
        <div class="row g-4 align-items-start">
            <aside class="col-lg-3">
                <nav class="legal-toc" aria-label="Nội dung chính">
                    <strong>Nội dung chính</strong>
                    @foreach ($sections as $section)
                        <a href="#muc-{{ $loop->iteration }}">{{ $section['heading'] }}</a>
                    @endforeach
                    <button class="btn btn-outline-primary btn-sm w-100 mt-2" type="button" data-consult="Hỏi về {{ $title }}">Cần giải thích thêm</button>
                </nav>
            </aside>
            <div class="col-lg-9">
                @if ($notice)
                    <div class="legal-notice"><x-icon name="info" /><p>{{ $notice }}</p></div>
                @endif
                <div class="legal-document prose">
                    @foreach ($sections as $section)
                        <article id="muc-{{ $loop->iteration }}">
                            <h2>{{ $section['heading'] }}</h2>
                            @if (filled($section['content'] ?? null))
                                <p>{{ $section['content'] }}</p>
                            @endif
                            @if (! empty($section['items']))
                                <ul>
                                    @foreach ($section['items'] as $item)
                                        <li>{{ $item }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </article>
                    @endforeach
                </div>
                <div class="legal-contact">
                    <div>
                        <p class="eyebrow">Thông tin liên hệ</p>
                        <h2 class="h3">Cần trao đổi thêm về nội dung này?</h2>
                        <p class="mb-0 text-muted">Gửi nội dung cần làm rõ hoặc liên hệ trực tiếp qua hotline và email được công bố trên website.</p>
                    </div>
                    <div class="btn-row">
                        <a class="btn btn-primary" href="{{ route('contact') }}">Gửi yêu cầu</a>
                        <a class="btn btn-outline-primary" href="tel:{{ site_config('phone_href') }}"><x-icon name="phone" /> {{ site_config('phone') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
