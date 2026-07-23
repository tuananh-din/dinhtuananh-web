@extends('admin.layouts.master')
@section('content')
<div class="main-content">
    <div class="page-header">
        <div class="header-sub-title">
            <nav class="breadcrumb breadcrumb-dash">
                <a class="breadcrumb-item">Kỹ năng</a>
                <span class="breadcrumb-item active">Danh sách</span>
            </nav>
        </div>
        
    </div>
    <div class="card">
        <div class="card-body">
            <h4>Danh sách Kỹ năng</h4>
            <a href="{{route('skill.create')}}" class="btn btn-primary m-r-5">Thêm mới</a>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Tên</th>
                            <th scope="col">Mô tả</th>
                            <th scope="col">Phần trăm</th>
                            <th scope="col">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($skills as $row)
                            <tr>
                                <th scope="row">{{$row->name}}</th>
                                <td>{{$row->description}}</td>
                                <td>{{$row->number}}</td>
                                <td>
                                    <a href="{{ route('skill.edit',$row->id) }}"> 
                                        <button class="btn btn-icon btn-primary btn-rounded btn-tone">
                                            <i class="fas fa-edit"></i>
                                        </button> 
                                    </a>
                                    <form action="{{ route('skill.delete',$row->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Bạn có chắc muốn xoá?')">
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
                {{ $skills->links('vendor.pagination') }}

            </div>
        </div>
    </div>
</div>
@endsection