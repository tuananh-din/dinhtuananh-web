@extends('admin.layouts.master')
@section('content')
<div class="main-content"><div class="card"><div class="card-body">
    <h4>Thùng rác bài viết</h4>
    <a href="{{ route('admin.blog') }}" class="btn btn-primary mb-3">Quay lại danh sách</a>
    <div class="table-responsive"><table class="table"><thead><tr><th>Tiêu đề</th><th>Đã xóa lúc</th><th>Hành động</th></tr></thead><tbody>
    @forelse($blogs as $blog)
    <tr><td>{{ $blog->title }}</td><td>{{ optional($blog->deleted_at)->format('d/m/Y H:i') }}</td><td>
        <form method="POST" action="{{ route('blog.restore', $blog->id) }}" class="d-inline">@csrf<button class="btn btn-sm btn-success">Khôi phục</button></form>
        <form method="POST" action="{{ route('blog.force-delete', $blog->id) }}" class="d-inline" onsubmit="return confirm('Xóa vĩnh viễn bài viết này?')">@csrf<button class="btn btn-sm btn-danger">Xóa vĩnh viễn</button></form>
    </td></tr>
    @empty <tr><td colspan="3">Thùng rác đang trống.</td></tr>@endforelse
    </tbody></table></div>{{ $blogs->links('vendor.pagination') }}
</div></div></div>
@endsection
