@extends('layouts.admin')

@section('title', 'Sửa dịch vụ vận hành')
@section('header_title', 'Sửa dịch vụ vận hành')
@section('admin_content')
<form action="{{ route('admin.operation-services.update', $service) }}" method="POST">@csrf @method('PUT')
    <div class="card">@include('admin.operation_services._form')<div class="card-footer"><button class="btn btn-primary">Cập nhật</button><a href="{{ route('admin.operation-services.index') }}" class="btn btn-default">Hủy</a></div></div>
</form>
@stop
