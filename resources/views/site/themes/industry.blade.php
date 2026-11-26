@extends('layouts.master')

@section('content')
    @php($faqs = $industry->faqItems())
    <x-hero variant="catalog" eyebrow="Kho giao diện theo ngành" :title="$heading" :lead="$industry->description"
        :image="$industry->bannerImage ? app(\App\Domain\Media\Actions\BuildResponsiveImage::class)->execute($industry->bannerImage, [480, 800, 1200]) : null" :imageAlt="$heading" />

    <section class="section" id="mau">
        <div class="container">
            <x-section-head :title="$themes->count().' giao diện cho ngành '.mb_strtolower($industry->name)" :href="route('themes.index')" link="Xem toàn bộ kho" />
            <div class="row g-4 row-cols-1 row-cols-md-2 row-cols-lg-3">
                @foreach ($themes as $theme)
                    <div class="col"><x-card.theme :theme="$theme" /></div>
                @endforeach
            </div>
        </div>
    </section>

    @if (filled($industry->content) || $faqs !== [])
        <section class="section section--soft">
            <div class="container">
                <div class="industry-column">
                    @if (filled($industry->content))
                        <div class="prose">{!! $industry->body_html !!}</div>
                    @endif
                    @if ($faqs !== [])
                        <section class="industry-faq" aria-labelledby="industry-faq-title">
                            <h2 id="industry-faq-title">Câu hỏi thường gặp</h2>
                            <x-faq :items="$faqs" :columns="1" name="industry-faq" />
                        </section>
                    @endif
                </div>
            </div>
        </section>
    @endif

    @if ($projects->isNotEmpty())
        <section class="section">
            <div class="container">
                <x-section-head eyebrow="Dự án" title="Dự án cùng ngành" :href="route('projects.index')" link="Xem tất cả dự án" />
                <div class="row g-4 row-cols-1 row-cols-md-2 row-cols-lg-3">
                    @foreach ($projects as $project)
                        <div class="col"><x-card.project :project="$project" /></div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($otherIndustries->isNotEmpty())
        <section class="section">
            <div class="container">
                <x-section-head title="Ngành khác" />
                <x-industry-links :industries="$otherIndustries" />
            </div>
        </section>
    @endif

    <x-lead-section id="tu-van-mau" eyebrow="Nhận tư vấn mẫu" :title="'Cần '.mb_strtolower($heading).'? Gửi yêu cầu để được tư vấn.'"
        text="Chúng tôi trao đổi nhu cầu, đề xuất mẫu gần nhất và xác định phần cần chỉnh theo doanh nghiệp trước khi báo giá."
        :need="$heading" />
@endsection
