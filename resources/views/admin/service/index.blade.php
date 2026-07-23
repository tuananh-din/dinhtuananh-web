@extends('admin.layouts.master')
@section('content')
<div class="main-content">
    <div class="page-header">
        <div class="header-sub-title">
            <nav class="breadcrumb breadcrumb-dash">
                <a class="breadcrumb-item">Ngành nghề</a>
                <span class="breadcrumb-item active">Danh sách</span>
            </nav>
        </div>
        
    </div>
    <div class="card">
        <div class="card-body">
            <h4>Danh sách Ngành nghề</h4>
            <a href="{{route('service.create')}}" class="btn btn-primary m-r-5">Thêm mới</a>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Tiêu đề</th>
                            <th scope="col">Mô tả</th>
                            <th scope="col">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($services as $row)
                            <tr>
                                <th scope="row">{{$row->title}}</th>
                                <td>{{$row->description}}</td>
                                <td>
                                    <a href="{{ route('service.edit',$row->id) }}"> 
                                        <button class="btn btn-icon btn-primary btn-rounded btn-tone">
                                            <i class="fas fa-edit"></i>
                                        </button> 
                                    </a>
                                    <form action="{{ route('service.delete',$row->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Bạn có chắc muốn xoá?')">
                                        @csrf
                                        <button type="submit" class="btn btn-icon btn-danger btn-rounded btn-tone">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        
                       
                    </tbody>
                </table>
                {{ $services->links('vendor.pagination') }}

            </div>
        </div>
    </div>
</div>
@endsection