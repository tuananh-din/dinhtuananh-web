@extends('admin.layouts.master')

@section('content')
<div class="main-content">
    <div class="page-header">
        <div class="header-sub-title">
            <nav class="breadcrumb breadcrumb-dash">
                <span class="breadcrumb-item active">Case Study</span>
            </nav>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h4>Danh sách Case Study</h4>
            <a href="{{ route('case-study.create') }}" class="btn btn-primary m-r-5">Thêm mới</a>

            <div class="table-responsive mt-3">
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
                        @forelse ($caseStudies as $caseStudy)
                        <tr>
                            <th scope="row">{{ $caseStudy->title }}</th>
                            <td><img src="{{ $caseStudy->image_url }}" alt="" height="70"></td>
                            <td><span class="badge {{ $caseStudy->is_published ? 'badge-success' : 'badge-secondary' }}">{{ $caseStudy->is_published ? 'Đã đăng' : 'Nháp' }}</span></td>
                            <td>
                                <a href="{{ route('case-study.preview', $caseStudy->id) }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary">Xem trước</a>
                                <a href="{{ route('case-study.edit', $caseStudy->id) }}" class="btn btn-icon btn-primary btn-rounded btn-tone"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('case-study.delete', $caseStudy->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xoá?')">
                                    @csrf
                                    <button type="submit" class="btn btn-icon btn-danger btn-rounded btn-tone"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted">Chưa có case study nào.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $caseStudies->links('vendor.pagination') }}
            </div>
        </div>
    </div>
</div>
@endsection
