{{-- Free tool pages: site header and footer, breadcrumbs, then the tool itself. --}}
@extends('layouts.site')

@section('content')
    <div class="tool-page">
        <div class="container pt-4">
            <x-breadcrumbs :items="$breadcrumbs" class="mb-0" />
        </div>
        @yield('tool-content')
        @include('tools.partials.more-tools')
    </div>
@endsection
