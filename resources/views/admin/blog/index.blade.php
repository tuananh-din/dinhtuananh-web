@extends('admin.layouts.master')
@section('content')
<div class="main-content">
    <div class="page-header">
        <div class="header-sub-title">
            <nav class="breadcrumb breadcrumb-dash">
                <a class="breadcrumb-item">Bài viết</a>
                <span class="breadcrumb-item active">Danh sách</span>
            </nav>
        </div>
        
    </div>
    <div class="card">
        <div class="card-body">
            <h4>Danh sách bài viết</h4>
            <p class="text-muted">Đã đăng: {{ $publishedCount }} &middot; Nháp: {{ $draftCount }}</p>
            <a href="{{route('blog.create')}}" class="btn btn-primary m-r-5">Thêm mới</a>
            <a href="{{ route('blog.trash') }}" class="btn btn-outline-secondary">Thùng rác</a>
            <form method="GET" action="{{ route('admin.blog') }}" class="form-inline mt-3 mb-3">
                <label class="sr-only" for="admin-blog-search">Tìm tiêu đề</label>
                <input id="admin-blog-search" name="search" value="{{ $search }}" class="form-control mr-2" placeholder="Tìm theo tiêu đề">
                <label class="sr-only" for="admin-blog-status">Trạng thái</label>
                <select id="admin-blog-status" name="status" class="form-control mr-2">
                    <option value="">Tất cả trạng thái</option>
                    <option value="published" {{ $status === 'published' ? 'selected' : '' }}>Đã đăng</option>
                    <option value="draft" {{ $status === 'draft' ? 'selected' : '' }}>Nháp</option>
                </select>
                <label class="sr-only" for="admin-blog-category">Chuyên mục</label>
                <select id="admin-blog-category" name="category" class="form-control mr-2">
                    <option value="">Tất cả chuyên mục</option>
                    @foreach($categories as $item)<option value="{{ $item->slug }}" {{ $category === $item->slug ? 'selected' : '' }}>{{ $item->name }}</option>@endforeach
                </select>
                <button class="btn btn-secondary" type="submit">Lọc</button>
            </form>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Tiêu đề</th>
                            <th scope="col">Ảnh</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($blogs as $row)
                            <tr>
                                <th scope="row">{{$row->title}}</th>
                                <td><img src="{{$row->image}}" height="100px"></td>
                                <td><span class="badge {{ $row->is_published ? 'badge-success' : 'badge-secondary' }}">{{ $row->is_published ? 'Đã đăng' : 'Nháp' }}</span></td>
                                <td>
                                    <a href="{{ route('blog.preview', $row->id) }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary">Xem trước</a>
                                    <a href="{{ route('blog.edit',$row->id) }}"> 
                                        <button class="btn btn-icon btn-primary btn-rounded btn-tone">
                                            <i class="fas fa-edit"></i>
                                        </button> 
                                    </a>
                                    <form action="{{ route('blog.delete',$row->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Bạn có chắc muốn xoá?')">
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
                {{ $blogs->links('vendor.pagination') }}

            </div>
        </div>
    </div>
</div>
@endsection
