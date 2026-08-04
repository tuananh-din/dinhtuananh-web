@extends('admin.layouts.master')
@section('content')

<!-- Content Wrapper START -->
<div class="main-content">
    <div class="page-header">
        <h2 class="header-title">Cập nhật bài viết</h2>
        <div class="header-sub-title">
            <nav class="breadcrumb breadcrumb-dash">
                <a class="breadcrumb-item">Bài viết</a>
                <span class="breadcrumb-item active">Cập nhật</span>
            </nav>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
          
            <a href="{{ route('admin.blog') }}" class="btn btn-primary m-r-5">Danh sách</a>
            <a href="{{ route('blog.preview', $blog->id) }}" target="_blank" rel="noopener" class="btn btn-secondary">Xem trước</a>
            <form class="forms-sample" action="{{ route('blog.store') }}" method="POST" enctype="multipart/form-data" id="form-validation">
                @csrf
                <input type="hidden" value="{{$blog->id}}" name="id">
                <div class="row">
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Cập nhật</h4>
                                <p class="card-description"> Thông tin chi tiết </p>
                                   
                                    <div class="form-group">
                                            <label for="title">Tiêu đề (*)</label>
                                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" id="title" value="{{ old('title', $blog->title) }}">
                                            <span class="mess-error" id="title_error"></span>
                                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>

                                    <div class="form-group">
                                        <label for="description">Mô tả</label>
                                        <textarea  name="description" class="form-control" id="description"  rows="6">{{$blog->description}}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="content">Nội dung (*)</label>
                                        <textarea id="content" name="content" class="form-control content @error('content') is-invalid @enderror">{!! old('content', $blog->content) !!}</textarea>
                                        <span class="mess-error" id="content_error"></span>
                                        @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Chuyên mục</label>
                                        @php($selectedCategories = old('categories', $blog->categories->pluck('id')->all()))
                                        <div class="border rounded p-3">
                                            @foreach($categories as $category)
                                            <div class="form-check">
                                                <input id="category-{{ $category->id }}" class="form-check-input" type="checkbox" name="categories[]" value="{{ $category->id }}" {{ in_array($category->id, $selectedCategories) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="category-{{ $category->id }}">{{ $category->name }}</label>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    
                                    <div class="form-check mb-3">
                                        <input id="is_published" class="form-check-input" type="checkbox" name="is_published" value="1" {{ old('is_published', $blog->is_published) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_published">Đăng bài</label>
                                    </div>
                                    <div class="form-group">
                                        <img id="preview" src="{{ $blog->image ?? 'app/assets/images/others/thumb-16.jpg' }}" alt="">
                                        <div class="file-input">
                                            <input class="choose @error('image') is-invalid @enderror" type="file" name="image" accept="image/*">
                                            <span class="button">Thêm hình ảnh</span>
                                            <span class="label"></span>
                                        </div>
                                        <small class="form-text text-muted">Khuyến nghị: ảnh ngang 16:9, tối thiểu 1200×675 px, dưới 5 MB.</small>
                                        <small id="blog-image-aspect-warning" class="form-text text-warning" hidden></small>
                                        @error('image')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="m-t-25">
                                        <div class="form-group">
                                            <label for="inputEmail4">Title</label>
                                            <input name="title_seo" type="text" class="form-control" value="{{$blog->title_seo}}">
                                        </div>
                                         
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea  name="desc_seo" class="form-control" id="description"  rows="6">{{$blog->desc_seo}}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputEmail4">Keyword</label>
                                            <input name="key_seo" type="text" class="form-control" value="{{$blog->key_seo}}">
                                        </div>
                                        
                                    </div>
                                   
                                    <button class="btn btn-success btn-tone m-r-5" id="btn-news">Thêm mới</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/bootstrap.tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
<script src="{{ asset('app/assets/vendors/jquery-validation/jquery.validate.min.js')}}"></script>
{{-- <script src="https://cdn.tiny.cloud/1/lt5xqigvc4dhm8ov8o1d1657z3mpluws5ozsa6bo4ukjkjby/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script> --}}
<script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>
<script>window.ckUploadConfig = { url: '{{ route("image.upload") }}', token: '{{ csrf_token() }}' };</script>
<script src="{{ asset('app/assets/js/ckeditor-csrf-upload-adapter.js') }}"></script>

<script>
    let myEditor;
    ClassicEditor
        .create( document.querySelector( '#content' ),{
            extraPlugins: [ CsrfUploadAdapterPlugin ],
        })  
        .then( editor => {
            myEditor = editor;
        } )
        .catch( error => {
            console.error( error );
        } );

</script>


@endsection
