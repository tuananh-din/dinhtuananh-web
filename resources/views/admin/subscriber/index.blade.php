@extends('admin.layouts.master')

@section('content')
<div class="main-content">
    <div class="page-header"><div class="header-sub-title"><nav class="breadcrumb breadcrumb-dash"><a class="breadcrumb-item">Đăng ký email</a><span class="breadcrumb-item active">Danh sách</span></nav></div></div>
    <div class="card"><div class="card-body">
        <h4>Danh sách đăng ký email</h4>
        <div class="d-flex flex-wrap align-items-center justify-content-between m-b-15">
            <div class="lead-counts"><span class="badge badge-primary m-r-5">Tổng {{ $total }}</span>@foreach($counts as $sourceName => $count)<span class="badge badge-default m-r-5">{{ $sourceName }}: {{ $count }}</span>@endforeach</div>
            <form method="GET" action="{{ route('admin.subscriber') }}" class="d-flex flex-wrap align-items-center">
                <input type="search" name="search" value="{{ $search }}" class="form-control m-r-10" placeholder="Tìm email">
                <select name="source" class="form-control m-r-10"><option value="">Tất cả nguồn</option>@foreach($counts->keys() as $sourceName)<option value="{{ $sourceName }}" {{ $source === $sourceName ? 'selected' : '' }}>{{ $sourceName }}</option>@endforeach</select>
                <button type="submit" class="btn btn-primary btn-tone m-r-10">Tìm</button><a href="{{ route('subscriber.export', request()->query()) }}" class="btn btn-default">Xuất CSV</a>
            </form>
        </div>
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
        <div class="table-responsive"><table class="table"><thead><tr><th>Email</th><th>Nguồn</th><th>Ngày đăng ký</th><th>Thao tác</th></tr></thead><tbody>
            @forelse($subscribers as $subscriber)
            <tr><td>{{ $subscriber->email }}</td><td>{{ $subscriber->source ?: '-' }}</td><td>{{ optional($subscriber->created_at)->format('d/m/Y H:i') }}</td><td><form action="{{ route('subscriber.delete', $subscriber->id) }}" method="POST" onsubmit="return confirm('Xóa email đăng ký này?');">@csrf<button type="submit" class="btn btn-danger btn-tone btn-sm">Xóa</button></form></td></tr>
            @empty
            <tr><td colspan="4" class="text-center">Chưa có email đăng ký phù hợp.</td></tr>
            @endforelse
        </tbody></table>{{ $subscribers->links('vendor.pagination') }}</div>
    </div></div>
</div>
@endsection
