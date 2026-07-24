@extends('admin.layouts.master')
@section('content')
<div class="main-content">
    <div class="page-header">
        <h2 class="header-title">Th&#234;m m&#7899;i kh&#243;a h&#7885;c</h2>
        <div class="header-sub-title">
            <nav class="breadcrumb breadcrumb-dash">
                <a class="breadcrumb-item">Kh&#243;a h&#7885;c</a>
                <span class="breadcrumb-item active">Th&#234;m m&#7899;i</span>
            </nav>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <a href="{{ route('admin.course') }}" class="btn btn-primary m-r-5">Danh s&#225;ch</a>
            <form class="forms-sample" action="{{ route('course.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Th&#244;ng tin kh&#243;a h&#7885;c</h4>
                                @if($errors->any())
                                    <div class="alert alert-danger">
                                        {{ $errors->first() }}
                                    </div>
                                @endif
                                <div class="form-group">
                                    <label>Ti&#234;u &#273;&#7873; (*)</label>
                                    <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                                </div>
                                <div class="form-group">
                                    <label>Slug (&#273;&#7875; tr&#7889;ng s&#7869; t&#7921; t&#7841;o)</label>
                                    <input type="text" name="slug" class="form-control" value="{{ old('slug') }}">
                                </div>
                                <div class="form-group">
                                    <label>M&#244; t&#7843; ng&#7855;n (*)</label>
                                    <textarea name="short_description" class="form-control" rows="4" required>{{ old('short_description') }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label>M&#244; t&#7843;</label>
                                    <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label>N&#7897;i dung</label>
                                    <textarea name="content" class="form-control" rows="8">{{ old('content') }}</textarea>
                                </div>
                                <div class="form-group">
                                    <img id="preview" src="app/assets/images/others/thumb-16.jpg" alt="">
                                    <div class="file-input">
                                        <input class="choose" type="file" name="thumbnail" accept="image/*">
                                        <span class="button">Th&#234;m &#7843;nh thumb</span>
                                        <span class="label"></span>
                                    </div>
                                    <small class="form-text text-muted">Khuy&#7871;n ngh&#7883;: &#7843;nh ngang 16:9, t&#7889;i thi&#7875;u 1200&#215;675 px, d&#432;&#7899;i 5 MB.</small>
                                </div>
                                <div class="form-group">
                                    <label>Gi&#225; g&#7889;c</label>
                                    <input type="number" step="0.01" min="0" name="price" class="form-control" value="{{ old('price') }}">
                                </div>
                                <div class="form-group">
                                    <label>Gi&#225; sale</label>
                                    <input type="number" step="0.01" min="0" name="sale_price" class="form-control @error('sale_price') is-invalid @enderror" value="{{ old('sale_price') }}">
                                    @error('sale_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="form-group">
                                    <label>N&#7873;n t&#7843;ng</label>
                                    <input type="text" name="platform" class="form-control" value="{{ old('platform') }}">
                                </div>
                                <div class="form-group">
                                    <label>C&#7845;p &#273;&#7897;</label>
                                    <input type="text" name="level" class="form-control" value="{{ old('level') }}">
                                </div>
                                <div class="form-group">
                                    <label>Th&#7901;i l&#432;&#7907;ng</label>
                                    <input type="text" name="duration_text" class="form-control" value="{{ old('duration_text') }}">
                                </div>
                                <div class="form-group">
                                    <label>H&#236;nh th&#7913;c h&#7885;c</label>
                                    <input type="text" name="format" class="form-control" value="{{ old('format') }}">
                                </div>
                                <div class="form-group">
                                    <label>CTA text</label>
                                    <input type="text" name="cta_text" class="form-control @error('cta_text') is-invalid @enderror" value="{{ old('cta_text') }}" maxlength="60" aria-describedby="cta-text-help">
                                    <small id="cta-text-help" class="form-text text-muted">T&#7889;i &#273;a 60 k&#253; t&#7921;.</small>
                                    @error('cta_text')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="form-group">
                                    <label>CTA link</label>
                                    <input type="text" name="cta_link" class="form-control @error('cta_link') is-invalid @enderror" value="{{ old('cta_link') }}">
                                    @error('cta_link')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="form-group">
                                    <label>Th&#7913; t&#7921; hi&#7875;n th&#7883;</label>
                                    <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', 0) }}">
                                </div>
                                <div class="form-group">
                                    <label>SEO title</label>
                                    <input type="text" name="seo_title" class="form-control @error('seo_title') is-invalid @enderror" value="{{ old('seo_title') }}" maxlength="60" aria-describedby="seo-title-help">
                                    <small id="seo-title-help" class="form-text text-muted">T&#7889;i &#273;a 60 k&#253; t&#7921;.</small>
                                    @error('seo_title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="form-group">
                                    <label>SEO description</label>
                                    <textarea name="seo_description" class="form-control @error('seo_description') is-invalid @enderror" rows="4" maxlength="155" aria-describedby="seo-description-help">{{ old('seo_description') }}</textarea>
                                    <small id="seo-description-help" class="form-text text-muted">T&#7889;i &#273;a 155 k&#253; t&#7921;.</small>
                                    @error('seo_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="is_featured" {{ old('is_featured') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_featured">Kh&#243;a h&#7885;c n&#7893;i b&#7853;t</label>
                                </div>
                                <div class="form-check mb-4">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', 1) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">&#272;ang hi&#7875;n th&#7883;</label>
                                </div>
                                <button class="btn btn-success btn-tone m-r-5">Th&#234;m m&#7899;i</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
