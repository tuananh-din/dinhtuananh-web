@extends('admin.layouts.master')
@section('content')
<div class="main-content">
    <div class="page-header">
        <h2 class="header-title">Th&#234;m m&#7899;i testimonial</h2>
        <div class="header-sub-title">
            <nav class="breadcrumb breadcrumb-dash">
                <a class="breadcrumb-item">Testimonial</a>
                <span class="breadcrumb-item active">Th&#234;m m&#7899;i</span>
            </nav>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <a href="{{ route('admin.testimonial') }}" class="btn btn-primary m-r-5">Danh s&#225;ch</a>
            <form class="forms-sample" action="{{ route('testimonial.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                @if($errors->any())
                                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                                @endif
                                <div class="form-group">
                                    <label>T&#234;n (*)</label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="form-group">
                                    <label>Ch&#7913;c danh</label>
                                    <input type="text" name="job_title" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>C&#244;ng ty</label>
                                    <input type="text" name="company" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>N&#7897;i dung (*)</label>
                                    <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="5" required>{{ old('content') }}</textarea>
                                    @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="form-group">
                                    <label>Rating (1-5)</label>
                                    <input type="number" min="1" max="5" name="rating" class="form-control @error('rating') is-invalid @enderror" value="{{ old('rating') }}">
                                    @error('rating')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="form-group">
                                    <label>&#7842;nh &#273;&#7841;i di&#7879;n</label>
                                    <input type="file" name="avatar" class="form-control-file @error('avatar') is-invalid @enderror" accept="image/*">
                                    <small class="form-text text-muted">Khuyến nghị: ảnh vuông, tối thiểu 600×600 px, dưới 5 MB.</small>
                                    <small id="testimonial-avatar-aspect-warning" class="form-text text-warning" hidden></small>
                                    @error('avatar')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                                <div class="form-group">
                                    <label>Th&#7913; t&#7921; hi&#7875;n th&#7883;</label>
                                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="form-control @error('sort_order') is-invalid @enderror">
                                    @error('sort_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="is_featured">
                                    <label class="form-check-label" for="is_featured">N&#7893;i b&#7853;t</label>
                                </div>
                                <div class="form-check mb-4">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" checked>
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
