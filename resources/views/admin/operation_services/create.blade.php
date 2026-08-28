@extends('layouts.admin')

@section('title', 'Thêm dịch vụ vận hành')
@section('header_title', 'Thêm dịch vụ vận hành')
@section('admin_content')
<form action="{{ route('admin.operation-services.store') }}" method="POST">@csrf
    <div class="card">@include('admin.operation_services._form')<div class="card-footer"><button class="btn btn-primary">Lưu dịch vụ</button><a href="{{ route('admin.operation-services.index') }}" class="btn btn-default">Hủy</a></div></div>
</form>
@stop
