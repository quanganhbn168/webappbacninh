{{-- Agency / white-label band, shared by the home and services pages. --}}
<section class="split-band" id="agency">
    <div class="container">
        <div class="split-band__grid">
            <div>
                <h2>{{ $title ?? 'Hợp tác cùng Agency / White Label' }}</h2>
                <p class="lead-text">{{ $text ?? 'Chúng tôi là đối tác tin cậy cho các Agency, Studio, Media… cung cấp giải pháp website và phần mềm dưới thương hiệu của bạn.' }}</p>
                <div class="btn-row">
                    <a class="btn btn-primary" href="{{ route('agency') }}">Trao đổi hợp tác <x-icon name="arrow-right" /></a>
                    <button class="btn btn-outline-dark" type="button" data-bs-toggle="modal" data-bs-target="#consult-modal">Nhận hồ sơ năng lực</button>
                </div>
            </div>
            <x-img :src="asset('images/agency-handshake.webp')" sizes="(min-width: 992px) 45vw, 100vw" class="split-band__image" alt="Hai đối tác trao đổi hợp tác" width="640" height="420" loading="lazy" />
            <div>
                <ul class="check-list check-list--gold">
                    @foreach ($benefits ?? ['Giải pháp linh hoạt, chi phí tốt', 'Đảm bảo chất lượng & tiến độ', 'Hỗ trợ kỹ thuật chuyên sâu', 'Đồng hành lâu dài, cùng phát triển'] as $benefit)
                        <li>{{ $benefit }}</li>
                    @endforeach
                </ul>
                <p class="split-band__note text-hand" aria-hidden="true">Cùng nhau tạo nên giá trị lớn hơn!</p>
            </div>
        </div>
    </div>
</section>
