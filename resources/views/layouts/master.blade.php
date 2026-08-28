@include('frontend.site.layouts.head')
@include('frontend.site.layouts.header')

@if (session('success'))
    <div class="container pt-3"><div class="alert alert-success mb-0">{{ session('success') }}</div></div>
@endif

@include($contentView)
@include('frontend.site.layouts.footer')
@include('frontend.site.layouts.scripts')
<script src="{{ frontend_asset('assets/js/lead-forms.js') }}"></script>
