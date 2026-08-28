@extends('layouts.admin')

@section('title', 'Cài đặt hệ thống')
@section('header_title', 'Cài đặt hệ thống')

@section('breadcrumb')
    <li class="breadcrumb-item active">Cài đặt</li>
@stop

@section('admin_content')
    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Chưa thể lưu cấu hình.</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-sliders-h mr-1"></i> Cài đặt chung</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="general-name">Tên hiển thị website</label>
                            <input id="general-name" class="form-control" name="general[name]" value="{{ old('general.name', $general->name) }}" required>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="general-company-name">Tên pháp lý / doanh nghiệp</label>
                            <input id="general-company-name" class="form-control" name="general[company_name]" value="{{ old('general.company_name', $general->company_name) }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="general-language">Ngôn ngữ</label>
                            <input id="general-language" class="form-control" name="general[default_language]" value="{{ old('general.default_language', $general->default_language) }}" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-globe mr-1"></i> Cài đặt website</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="website-url">URL chính thức</label>
                    <input id="website-url" class="form-control" type="url" name="website[site_url]" value="{{ old('website.site_url', $website->site_url) }}" required>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <x-admin.image-uploader name="website[site_logo_wide]" label="Logo ngang" :value="old('website.site_logo_wide', $website->site_logo_wide)" height="90" />
                    </div>
                    <div class="col-md-3 mb-3 bg-dark rounded pt-2">
                        <x-admin.image-uploader name="website[site_logo_white]" label="Logo trắng" :value="old('website.site_logo_white', $website->site_logo_white)" height="90" />
                    </div>
                    <div class="col-md-3 mb-3">
                        <x-admin.image-uploader name="website[site_logo_square]" label="Logo vuông" :value="old('website.site_logo_square', $website->site_logo_square)" ratio="1x1" height="90" />
                    </div>
                    <div class="col-md-3 mb-3">
                        <x-admin.image-uploader name="website[site_favicon]" label="Favicon" :value="old('website.site_favicon', $website->site_favicon)" ratio="1x1" height="90" />
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-outline card-success">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-search mr-1"></i> Cài đặt SEO</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="seo-title">SEO title mặc định</label>
                    <input id="seo-title" class="form-control" name="seo[default_meta_title]" value="{{ old('seo.default_meta_title', $seo->default_meta_title) }}">
                </div>
                <div class="form-group">
                    <label for="seo-description">SEO description mặc định</label>
                    <textarea id="seo-description" class="form-control" name="seo[default_meta_description]" rows="3">{{ old('seo.default_meta_description', $seo->default_meta_description) }}</textarea>
                </div>
                <div class="form-group">
                    <label for="seo-page-meta">SEO từng trang (JSON)</label>
                    <textarea id="seo-page-meta" class="form-control font-monospace" name="seo[page_meta_json]" rows="10" spellcheck="false">{{ old('seo.page_meta_json', json_encode($seo->page_meta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) }}</textarea>
                    <small class="form-text text-muted">Có thể chỉnh title, description, keywords, og_image, canonical_url và robots theo các key: home, about, contact, pricing, agency, services, themes, projects, articles, operations.</small>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="seo-keywords">Từ khoá mặc định</label>
                            <input id="seo-keywords" class="form-control" name="seo[default_meta_keywords]" value="{{ old('seo.default_meta_keywords', $seo->default_meta_keywords) }}">
                        </div>
                        <div class="form-group">
                            <label for="seo-verification">Google Search Console verification</label>
                            <input id="seo-verification" class="form-control" name="seo[google_site_verification]" value="{{ old('seo.google_site_verification', $seo->google_site_verification) }}">
                        </div>
                        <div class="form-group mb-0">
                            <label for="seo-analytics">Google Analytics Measurement ID</label>
                            <input id="seo-analytics" class="form-control" name="seo[google_analytics_id]" value="{{ old('seo.google_analytics_id', $seo->google_analytics_id) }}" placeholder="G-XXXXXXXXXX">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <x-admin.image-uploader name="seo[default_og_image]" label="Ảnh OG mặc định" :value="old('seo.default_og_image', $seo->default_og_image)" ratio="16x9" />
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-outline card-warning">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-phone-alt mr-1"></i> Cài đặt liên lạc</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="contact-phone">Số điện thoại hiển thị</label>
                            <input id="contact-phone" class="form-control" name="contact[phone]" value="{{ old('contact.phone', $contact->phone) }}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="contact-phone-href">Số gọi (không khoảng trắng)</label>
                            <input id="contact-phone-href" class="form-control" name="contact[phone_href]" value="{{ old('contact.phone_href', $contact->phone_href) }}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="contact-email">Email</label>
                            <input id="contact-email" class="form-control" type="email" name="contact[email]" value="{{ old('contact.email', $contact->email) }}" required>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="form-group mb-md-0">
                            <label for="contact-address">Địa chỉ</label>
                            <input id="contact-address" class="form-control" name="contact[address]" value="{{ old('contact.address', $contact->address) }}">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group mb-0">
                            <label for="contact-working-time">Thời gian làm việc</label>
                            <input id="contact-working-time" class="form-control" name="contact[working_time]" value="{{ old('contact.working_time', $contact->working_time) }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-outline card-secondary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-share-alt mr-1"></i> Liên kết mạng xã hội</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach ([
                        'facebook' => ['Facebook', 'fab fa-facebook'],
                        'messenger' => ['Messenger', 'fab fa-facebook-messenger'],
                        'zalo' => ['Zalo', 'fas fa-comment-dots'],
                        'telegram' => ['Telegram', 'fab fa-telegram-plane'],
                        'wechat' => ['WeChat', 'fab fa-weixin'],
                        'whatsapp' => ['WhatsApp', 'fab fa-whatsapp'],
                        'youtube' => ['YouTube', 'fab fa-youtube'],
                    ] as $field => [$label, $icon])
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="social-{{ $field }}"><i class="{{ $icon }} mr-1"></i>{{ $label }}</label>
                                <input id="social-{{ $field }}" class="form-control" name="social[{{ $field }}]" value="{{ old('social.'.$field, $social->{$field}) }}" placeholder="https://... hoặc liên kết liên hệ">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="card-footer text-right">
                <button class="btn btn-primary" type="submit"><i class="fas fa-save mr-1"></i>Lưu toàn bộ cấu hình</button>
            </div>
        </div>
    </form>

    <div class="card card-outline card-light">
        <div class="card-header">
            <h3 class="card-title">Kiểm tra gửi email</h3>
        </div>
        <form action="{{ route('admin.settings.test-mail') }}" method="POST" class="card-body">
            @csrf
            <div class="input-group">
                <input class="form-control" name="email" type="email" placeholder="email@domain.com" required>
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit">Gửi email thử</button>
                </div>
            </div>
        </form>
    </div>
@stop
