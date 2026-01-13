@props(['title', 'icon', 'description', 'link' => '#', 'badge' => null])

<div class="col-md-4 mb-4">
    <div class="glass-card p-4 h-100 position-relative">
        @if($badge)
            <span class="position-absolute top-0 end-0 m-3 badge bg-danger">
                {{ $badge }}
            </span>
        @endif
        
        <div class="app-card-icon text-primary">
            <i class="{{ $icon }} fa-2x"></i>
        </div>
        
        <h5 class="fw-bold mb-2">{{ $title }}</h5>
        <p class="text-muted small mb-3">
            {{ $description }}
        </p>
        
        <a href="{{ $link }}" class="text-decoration-none fw-bold stretched-link">
            Truy cập ngay <i class="fas fa-arrow-right ms-1"></i>
        </a>
    </div>
</div>