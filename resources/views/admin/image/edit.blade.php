@extends('admin.layouts.master')
@section('content')

<!-- Content Wrapper START -->
<div class="main-content">
    <div class="page-header">
        <h2 class="header-title">Chỉnh sửa hình ảnh</h2>
        <div class="header-sub-title">
            <nav class="breadcrumb breadcrumb-dash">
                <a class="breadcrumb-item">hình ảnh</a>
                <span class="breadcrumb-item active">Chỉnh sửa</span>
            </nav>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
          
            <a href="{{ route('admin.image') }}" class="btn btn-primary m-r-5">Danh sách</a>
            <form class="forms-sample" action="{{ route('image.store') }}" method="POST" enctype="multipart/form-data" id="form-validation">
                @csrf
                <input type="hidden" value="{{$image->id}}" name="id">
                <div class="row">
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Chỉnh sửa</h4>
                                <p class="card-description"> Thông tin chi tiết </p>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="title">Tiêu đề (*)</label>
                                            <input type="text" name="title" class="form-control" id="title" value="{{$image->title}}">
                                            <span class="mess-error" id="title_error"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="title">Nằm ở</label>
                                            <select class="form-control" name="type">
                                                <option @if($image->type == 0) selected @endif value="0" selected>Life Through Lens</option>
                                                <option @if($image->type == 1) selected @endif value="1" selected>Trang giới thiệu</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="description">Mô tả</label>
                                        <textarea  name="description" class="form-control" id="description"  rows="6">{{$image->description}}</textarea>
                                    </div>
                                  
                                    
                                    <div class="form-group">
                                        <img id="preview" src="{{ $image->image ?? 'app/assets/images/others/thumb-16.jpg'}}" alt="">
                                        <div class="file-input">
                                            <input class="choose" type="file" name="image" accept="image/*">
                                            <span class="button">Thêm hình ảnh</span>
                                            <span class="label"></span>
                                        </div>
                                    </div>
                                   
                                    <button class="btn btn-success btn-tone m-r-5" id="btn-news">Chỉnh sửa</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection