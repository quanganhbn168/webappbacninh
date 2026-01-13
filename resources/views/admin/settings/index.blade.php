@extends('layouts.admin')

@section('title', 'Cấu hình hệ thống')

@section('header_title', 'Cấu hình hệ thống')

@section('breadcrumb')
    <li class="breadcrumb-item active">Settings</li>
@stop

@section('admin_content')
    <div class="row">
        <div class="col-md-12">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card card-primary card-tabs shadow-lg border-0">
                    <div class="card-header p-0 pt-1 border-bottom-0">
                        <ul class="nav nav-tabs px-3" id="custom-tabs-one-tab" role="tablist">
                            @foreach($settings as $group => $items)
                                <li class="nav-item">
                                    <a class="nav-link {{ $loop->first ? 'active' : '' }} text-uppercase fw-bold py-2 px-3" 
                                       id="tab-{{ $group }}-tab" 
                                       data-toggle="pill" 
                                       href="#tab-{{ $group }}" 
                                       role="tab">
                                       <i class="fas fa-{{ $group == 'branding' ? 'image' : ($group == 'contact' ? 'address-card' : 'share-nodes') }} mr-1"></i>
                                       {{ ucfirst($group) }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="card-body p-3">
                        <div class="tab-content" id="custom-tabs-one-tabContent">
                            @foreach($settings as $group => $items)
                                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" 
                                     id="tab-{{ $group }}" 
                                     role="tabpanel">
                                    
                                    @if($group === 'branding')
                                        {{-- Custom Layout for Branding --}}
                                        <div class="row">
                                            <div class="col-md-6 border-right">
                                                <h5 class="text-primary mb-3"><i class="fas fa-image mr-1"></i> Logo & Nhận diện</h5>
                                                
                                                {{-- Site Name --}}
                                                <div class="form-group mb-3">
                                                    <label class="font-weight-bold">Tên Website (Site Name)</label>
                                                    <input type="text" name="site_name" class="form-control" value="{{ $items->firstWhere('key', 'site_name')->value ?? '' }}">
                                                    <small class="text-muted">Được sử dụng làm thẻ <code>alt</code> cho logo và tiêu đề trang.</small>
                                                </div>

                                                {{-- Logo Wide --}}
                                                <div class="form-group mb-4">
                                                    <label class="font-weight-bold d-block">Logo Ngang (Header)</label>
                                                    <div class="mb-2 p-2 bg-light border rounded text-center" style="min-height: 100px;">
                                                        <img src="{{ asset($items->firstWhere('key', 'site_logo_wide')->value ?? '') }}" 
                                                             id="preview-logo-wide" 
                                                             style="max-height: 80px; max-width: 100%; object-fit: contain;">
                                                    </div>
                                                    <div class="custom-file">
                                                        <input type="file" name="site_logo_wide" class="custom-file-input" id="input-logo-wide" accept="image/*" onchange="previewImage(this, 'preview-logo-wide')">
                                                        <label class="custom-file-label" for="input-logo-wide">Chọn file...</label>
                                                    </div>
                                                    <small class="text-muted">Kích thước: Chiều cao ~65-80px. Định dạng: PNG, SVG.</small>
                                                </div>

                                                {{-- Logo White --}}
                                                <div class="form-group mb-4">
                                                    <label class="font-weight-bold d-block">Logo Trắng (Nền đen)</label>
                                                    <div class="mb-2 p-2 bg-dark border rounded text-center" style="min-height: 100px;">
                                                        <img src="{{ asset($items->firstWhere('key', 'site_logo_white')->value ?? '') }}" 
                                                             id="preview-logo-white" 
                                                             style="max-height: 80px; max-width: 100%; object-fit: contain;">
                                                    </div>
                                                    <div class="custom-file">
                                                        <input type="file" name="site_logo_white" class="custom-file-input" id="input-logo-white" accept="image/*" onchange="previewImage(this, 'preview-logo-white')">
                                                        <label class="custom-file-label" for="input-logo-white">Chọn file...</label>
                                                    </div>
                                                    <small class="text-muted">Dùng cho Dark Mode / Footer tối màu. Định dạng: PNG, SVG (Màu trắng).</small>
                                                </div>

                                                {{-- Logo Square --}}
                                                <div class="form-group mb-4">
                                                    <label class="font-weight-bold d-block">Logo Vuông (Mobile / Footer)</label>
                                                    <div class="mb-2 p-2 bg-light border rounded text-center d-inline-block" style="min-width: 100px; min-height: 100px;">
                                                        <img src="{{ asset($items->firstWhere('key', 'site_logo_square')->value ?? '') }}" 
                                                             id="preview-logo-square" 
                                                             style="max-height: 80px; max-width: 100%; object-fit: contain;">
                                                    </div>
                                                    <div class="custom-file">
                                                        <input type="file" name="site_logo_square" class="custom-file-input" id="input-logo-square" accept="image/*" onchange="previewImage(this, 'preview-logo-square')">
                                                        <label class="custom-file-label" for="input-logo-square">Chọn file...</label>
                                                    </div>
                                                    <small class="text-muted">Kích thước: Vuông (1:1). Định dạng: PNG, SVG.</small>
                                                </div>
                                            </div>

                                            <div class="col-md-6 pl-md-4">
                                                <h5 class="text-primary mb-3"><i class="fas fa-globe mr-1"></i> Web App Icon</h5>

                                                {{-- Favicon --}}
                                                <div class="form-group mb-4">
                                                    <label class="font-weight-bold d-block">Favicon (Tab trình duyệt)</label>
                                                    <div class="mb-2 p-2 bg-light border rounded text-center d-inline-block" style="min-width: 64px; min-height: 64px;">
                                                        <img src="{{ asset($items->firstWhere('key', 'site_favicon')->value ?? '') }}" 
                                                             id="preview-favicon" 
                                                             style="max-height: 48px; max-width: 100%; object-fit: contain;">
                                                    </div>
                                                    <div class="custom-file">
                                                        <input type="file" name="site_favicon" class="custom-file-input" id="input-favicon" accept="image/*,image/x-icon" onchange="previewImage(this, 'preview-favicon')">
                                                        <label class="custom-file-label" for="input-favicon">Chọn file...</label>
                                                    </div>
                                                    <small class="text-muted">Định dạng: ICO, PNG, SVG.</small>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        {{-- Generic Loop for Other Groups --}}
                                        @foreach($items as $setting)
                                            <div class="form-group row mb-2 border-bottom pb-2">
                                                <label class="col-sm-3 col-form-label font-weight-bold text-muted small">
                                                    {{ ucfirst(str_replace('_', ' ', $setting->key)) }}
                                                    <small class="d-block text-gray font-weight-normal"><code>{{ $setting->key }}</code></small>
                                                </label>
                                                <div class="col-sm-9">
                                                    @if(Str::contains($setting->key, 'email'))
                                                        <input type="email" name="{{ $setting->key }}" class="form-control" value="{{ $setting->value }}">
                                                    @elseif(Str::contains($setting->key, ['og_image']))
                                                         {{-- Fallback for OG Image - use direct upload too --}}
                                                         <div class="mb-2 p-2 bg-light border rounded text-center d-inline-block" style="min-width: 120px; min-height: 80px;">
                                                            <img src="{{ asset($setting->value) }}" 
                                                                 id="preview-{{ $setting->key }}" 
                                                                 style="max-height: 100px; max-width: 100%; object-fit: contain;">
                                                        </div>
                                                        <div class="custom-file">
                                                            <input type="file" name="{{ $setting->key }}" class="custom-file-input" id="input-{{ $setting->key }}" accept="image/*" onchange="previewImage(this, 'preview-{{ $setting->key }}')">
                                                            <label class="custom-file-label" for="input-{{ $setting->key }}">Chọn file...</label>
                                                        </div>
                                                    @elseif(Str::contains($setting->key, 'image') || Str::contains($setting->key, 'logo'))
                                                         {{-- Fallback for other image fields --}}
                                                         <div class="mb-2">
                                                            <x-admin.image-uploader 
                                                                :name="$setting->key" 
                                                                :value="$setting->value" 
                                                                :height="100"
                                                            />
                                                        </div>
                                                    @else
                                                        <input type="text" name="{{ $setting->key }}" class="form-control" value="{{ $setting->value }}">
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top-0 p-3 text-right">
                        <button type="submit" class="btn btn-primary px-4 shadow fw-bold">
                            <i class="fas fa-save mr-2"></i> Lưu cấu hình
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop

@push('js')
<script>
    function previewImage(input, previewId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                document.getElementById(previewId).src = e.target.result;
            }
            
            reader.readAsDataURL(input.files[0]);
            
            // Update label
            var fileName = input.files[0].name;
            $(input).next('.custom-file-label').html(fileName);
        }
    }
</script>
@endpush
