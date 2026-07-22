@extends('admin.layouts.master')
@section('content')

<!-- Content Wrapper START -->
<div class="main-content">
    <div class="page-header">
        <h2 class="header-title">Thêm mới Ngành nghề</h2>
        <div class="header-sub-title">
            <nav class="breadcrumb breadcrumb-dash">
                <a class="breadcrumb-item">Ngành nghề</a>
                <span class="breadcrumb-item active">Thêm mới</span>
            </nav>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
          
            <a href="{{ route('admin.service') }}" class="btn btn-primary m-r-5">Danh sách</a>
            <form class="forms-sample" action="{{ route('service.store') }}" method="POST" enctype="multipart/form-data" id="form-validation">
                @csrf
                <div class="row">
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Thêm mới</h4>
                                <p class="card-description"> Thông tin chi tiết </p>
                                   
                                    <div class="form-group">
                                            <label for="title">Tiêu đề (*)</label>
                                            <input type="text" name="title" class="form-control" id="title">
                                            <span class="mess-error" id="title_error"></span>
                                        </div>

                                    <div class="form-group">
                                        <label for="description">Mô tả</label>
                                        <textarea  name="description" class="form-control" id="description"  rows="6"></textarea>
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