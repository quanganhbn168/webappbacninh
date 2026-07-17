@extends('layouts.admin')

@section('title', 'Sửa nhóm dịch vụ')
@section('header_title', 'Sửa nhóm dịch vụ')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.service-categories.index') }}">Nhóm dịch vụ</a></li>
    <li class="breadcrumb-item active">{{ $serviceCategory->name }}</li>
@stop

@section('admin_content')
<form action="{{ route('admin.service-categories.update', $serviceCategory) }}" method="POST">
    @csrf @method('PUT')
    @include('admin.service_categories._form')
</form>
@stop
