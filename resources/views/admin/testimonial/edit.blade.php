@extends('admin.layouts.master')
@section('content')
<div class="main-content">
    <div class="page-header">
        <h2 class="header-title">Ch&#7881;nh s&#7917;a testimonial</h2>
        <div class="header-sub-title">
            <nav class="breadcrumb breadcrumb-dash">
                <a class="breadcrumb-item">Testimonial</a>
                <span class="breadcrumb-item active">Ch&#7881;nh s&#7917;a</span>
            </nav>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <a href="{{ route('admin.testimonial') }}" class="btn btn-primary m-r-5">Danh s&#225;ch</a>
            <form class="forms-sample" action="{{ route('testimonial.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="{{ $testimonial->id }}">
                <div class="row">
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                @if($errors->any())
                                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                                @endif
                                <div class="form-group">
                                    <label>T&#234;n (*)</label>
                                    <input type="text" name="name" class="form-control" value="{{ $testimonial->name }}" required>
                                </div>
                                <div class="form-group">
                                    <label>Ch&#7913;c danh</label>
                                    <input type="text" name="job_title" class="form-control" value="{{ $testimonial->job_title }}">
                                </div>
                                <div class="form-group">
                                    <label>C&#244;ng ty</label>
                                    <input type="text" name="company" class="form-control" value="{{ $testimonial->company }}">
                                </div>
                                <div class="form-group">
                                    <label>N&#7897;i dung (*)</label>
                                    <textarea name="content" class="form-control" rows="5" required>{{ $testimonial->content }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label>Rating (1-5)</label>
                                    <input type="number" min="1" max="5" name="rating" class="form-control" value="{{ $testimonial->rating }}">
                                </div>
                                <div class="form-group">
                                    @if($testimonial->avatar)
                                        <p><img src="{{ $testimonial->avatar }}" alt="{{ $testimonial->name }}" style="max-height:80px;"></p>
                                    @endif
                                    <label>&#7842;nh &#273;&#7841;i di&#7879;n</label>
                                    <input type="file" name="avatar" class="form-control-file" accept="image/*">
                                    <small class="form-text text-muted">Khuyến nghị: ảnh vuông, tối thiểu 600×600 px, dưới 5 MB.</small>
                                    <small id="testimonial-avatar-aspect-warning" class="form-text text-warning" hidden></small>
                                </div>
                                <div class="form-group">
                                    <label>Th&#7913; t&#7921; hi&#7875;n th&#7883;</label>
                                    <input type="number" name="sort_order" class="form-control" value="{{ $testimonial->sort_order }}">
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="is_featured" {{ $testimonial->is_featured ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_featured">N&#7893;i b&#7853;t</label>
                                </div>
                                <div class="form-check mb-4">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ $testimonial->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">&#272;ang hi&#7875;n th&#7883;</label>
                                </div>
                                <button class="btn btn-success btn-tone m-r-5">C&#7853;p nh&#7853;t</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
