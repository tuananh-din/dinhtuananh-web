@extends('admin.layouts.master')
@section('content')
<div class="main-content">
    <div class="page-header">
        <div class="header-sub-title">
            <nav class="breadcrumb breadcrumb-dash">
                <a class="breadcrumb-item">Hình ảnh</a>
                <span class="breadcrumb-item active">Danh sách</span>
            </nav>
        </div>
        
    </div>
    <div class="card">
        <div class="card-body">
            <h4>Danh sách Hình ảnh</h4>
            <a href="{{route('image.create')}}" class="btn btn-primary m-r-5">Thêm mới</a>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Nằm ở</th>
                            <th scope="col">Tiêu đề</th>
                            <th scope="col">Ảnh</th>
                            <th scope="col">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($images as $row)
                            <tr>
                                <td scope="col">
                                    @if($row->type == 0) Life Through Lens (*) @endif
                                    @if($row->type == 1) Trang giới thiệu @endif
                                </td>
                                <th scope="row">{{$row->title}}</th>
                                <td><img src="{{$row->image}}" height="100px"></td>
                                <td>
                                    <a href="{{ route('image.edit',$row->id) }}"> 
                                        <button class="btn btn-icon btn-primary btn-rounded btn-tone">
                                            <i class="fas fa-edit"></i>
                                        </button> 
                                    </a>
                                    <a href="{{ route('image.delete',$row->id) }}"  onclick="return confirm('Bạn có chắc muốn xoá?')">
                                        <button class="btn btn-icon btn-danger btn-rounded btn-tone">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        
                       
                    </tbody>
                </table>
                {{ $images->links('vendor.pagination') }}

            </div>
        </div>
    </div>
</div>
@endsection