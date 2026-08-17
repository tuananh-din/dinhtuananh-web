@extends('admin.layouts.master')

@section('content')
<div class="main-content">
    <div class="page-header"><h2 class="header-title">Sửa Case Study</h2></div>
    <div class="card"><div class="card-body">
        <a href="{{ route('admin.case-study') }}" class="btn btn-primary m-r-5">Danh sách</a>
        <a href="{{ route('case-study.preview', $caseStudy->id) }}" target="_blank" rel="noopener" class="btn btn-outline-secondary">Xem trước</a>
        <form action="{{ route('case-study.store') }}" method="POST" enctype="multipart/form-data" class="forms-sample mt-3">
            @csrf
            <input type="hidden" name="id" value="{{ $caseStudy->id }}">
            <div class="form-group">
                <label for="title">Tiêu đề (*)</label>
                <input id="title" name="title" value="{{ old('title', $caseStudy->title) }}" class="form-control @error('title') is-invalid @enderror" required>
                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="slug">Đường dẫn</label>
                <input id="slug" name="slug" value="{{ old('slug', $caseStudy->slug) }}" class="form-control @error('slug') is-invalid @enderror">
                <small class="form-text text-muted">URL: {{ url('/portfolio/'.$caseStudy->slug) }}. Để trống để tự tạo từ tiêu đề.</small>
                @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="image">Ảnh bìa</label>
                @if($caseStudy->image)<div class="mb-2"><img src="{{ $caseStudy->image_url }}" alt="" height="100"></div>@endif
                <input id="image" type="file" name="image" accept="image/*" class="form-control-file @error('image') is-invalid @enderror">
                <small class="form-text text-muted">Để trống để giữ ảnh hiện tại.</small>
                @error('image')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
            <div class="form-group"><label for="summary">Kết quả nổi bật</label><textarea id="summary" name="summary" rows="3" maxlength="500" class="form-control">{{ old('summary', $caseStudy->summary) }}</textarea></div>
            <div class="form-row">
                <div class="form-group col-md-6"><label for="client">Khách hàng</label><input id="client" name="client" value="{{ old('client', $caseStudy->client) }}" class="form-control"></div>
                <div class="form-group col-md-6"><label for="industry">Ngành</label><input id="industry" name="industry" value="{{ old('industry', $caseStudy->industry) }}" class="form-control"></div>
                <div class="form-group col-md-6"><label for="platforms">Nền tảng</label><input id="platforms" name="platforms" value="{{ old('platforms', $caseStudy->platforms) }}" class="form-control"></div>
                <div class="form-group col-md-3"><label for="duration">Thời gian</label><input id="duration" name="duration" value="{{ old('duration', $caseStudy->duration) }}" class="form-control"></div>
                <div class="form-group col-md-3"><label for="role">Vai trò</label><input id="role" name="role" value="{{ old('role', $caseStudy->role) }}" class="form-control"></div>
            </div>
            <h5 class="mt-3">KPI</h5>
            <div class="form-row">
                @for($number = 1; $number <= 4; $number++)
                <div class="form-group col-md-6"><label for="kpi_{{ $number }}_label">KPI {{ $number }} — nhãn</label><input id="kpi_{{ $number }}_label" name="kpi_{{ $number }}_label" value="{{ old('kpi_'.$number.'_label', data_get($caseStudy, 'kpi_'.$number.'_label')) }}" class="form-control"></div>
                <div class="form-group col-md-6"><label for="kpi_{{ $number }}_value">KPI {{ $number }} — giá trị</label><input id="kpi_{{ $number }}_value" name="kpi_{{ $number }}_value" value="{{ old('kpi_'.$number.'_value', data_get($caseStudy, 'kpi_'.$number.'_value')) }}" class="form-control"></div>
                @endfor
            </div>
            <div class="form-group"><label for="content">Nội dung (*)</label><textarea id="content" name="content" class="form-control @error('content') is-invalid @enderror">{{ old('content', $caseStudy->content) }}</textarea>@error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="form-group"><label for="title_seo">SEO title</label><input id="title_seo" name="title_seo" value="{{ old('title_seo', $caseStudy->title_seo) }}" class="form-control"></div>
            <div class="form-group"><label for="desc_seo">SEO description</label><textarea id="desc_seo" name="desc_seo" rows="3" class="form-control">{{ old('desc_seo', $caseStudy->desc_seo) }}</textarea></div>
            <div class="form-check mb-3"><input id="is_published" name="is_published" value="1" type="checkbox" class="form-check-input" {{ old('is_published', $caseStudy->is_published) ? 'checked' : '' }}><label for="is_published" class="form-check-label">Đăng case study</label></div>
            <button class="btn btn-success btn-tone">Lưu thay đổi</button>
        </form>
    </div></div>
</div>
<script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>
<script>ClassicEditor.create(document.querySelector('#content')).catch(error => console.error(error));</script>
@endsection
