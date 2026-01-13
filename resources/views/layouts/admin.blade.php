@extends('adminlte::page')

@section('plugins.Select2', true)

@section('title', $title ?? 'Admin - WebApp Bắc Ninh')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>@yield('header_title', 'Dashboard')</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
                @yield('breadcrumb')
            </ol>
        </div>
    </div>
@stop

@section('content')
    @yield('admin_content')
@stop

@section('footer')
    <div class="float-right d-none d-sm-block">
        <b>Version</b> 1.0.0
    </div>
    <strong>&copy; {{ now()->year }} <a href="{{ url('/') }}">WebApp Bắc Ninh</a>.</strong> All rights reserved.
@stop

@section('css')
    @stack('admin_css')
@stop

@section('js')
    @stack('admin_js')
@stop
