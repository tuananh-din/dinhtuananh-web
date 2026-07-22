@extends('admin.layouts.master')
@section('content')

<!-- Content Wrapper START -->
<div class="main-content">
    <div class="page-header">
        <h2 class="header-title">Thêm mới hình ảnh</h2>
        <div class="header-sub-title">
            <nav class="breadcrumb breadcrumb-dash">
                <a class="breadcrumb-item">hình ảnh</a>
                <span class="breadcrumb-item active">Thêm mới</span>
            </nav>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
          
            <a href="{{ route('admin.image') }}" class="btn btn-primary m-r-5">Danh sách</a>
            <form class="forms-sample" action="{{ route('image.store') }}" method="POST" enctype="multipart/form-data" id="form-validation">
                @csrf
                <div class="row">
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Thêm mới</h4>
                                <p class="card-description"> Thông tin chi tiết </p>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="title">Tiêu đề (*)</label>
                                            <input type="text" name="title" class="form-control" id="title">
                                            <span class="mess-error" id="title_error"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="title">Nằm ở</label>
                                            <select class="form-control" name="type">
                                                <option value="0" selected>Life Through Lens</option>
                                                <option value="1" >Trang Giới thiệu</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="description">Mô tả</label>
                                        <textarea  name="description" class="form-control" id="description"  rows="6"></textarea>
                                    </div>
                                  
                                    
                                    <div class="form-group">
                                        <img id="preview" src="app/assets/images/others/thumb-16.jpg" alt="">
                                        <div class="file-input">
                                            <input class="choose" type="file" name="image" accept="image/*">
                                            <span class="button">Thêm hình ảnh</span>
                                            <span class="label"></span>
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


@endsection