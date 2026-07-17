@extends('layouts.admin')

@section('title', 'Thêm nhóm dịch vụ')
@section('header_title', 'Thêm nhóm dịch vụ')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.service-categories.index') }}">Nhóm dịch vụ</a></li>
    <li class="breadcrumb-item active">Thêm mới</li>
@stop

@section('admin_content')
<form action="{{ route('admin.service-categories.store') }}" method="POST">
    @csrf
    @include('admin.service_categories._form', ['serviceCategory' => new \App\Models\ServiceCategory(['is_active' => true])])
</form>
@stop
