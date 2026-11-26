{{-- Free tool pages: site header and footer, breadcrumbs, then the tool itself. --}}
@extends('layouts.master')

@section('content')
    <div>
        <div class="container pt-4">
            <x-breadcrumbs :items="$breadcrumbs" class="mb-0" />
        </div>
        @yield('tool-content')
        @include('site.tools.partials.more-tools')
    </div>
@endsection
