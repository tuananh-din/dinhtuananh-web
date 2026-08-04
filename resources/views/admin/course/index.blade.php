@extends('admin.layouts.master')
@section('content')
<div class="main-content">
    <div class="page-header">
        <div class="header-sub-title">
            <nav class="breadcrumb breadcrumb-dash">
                <a class="breadcrumb-item">Kh&#243;a h&#7885;c</a>
                <span class="breadcrumb-item active">Danh s&#225;ch</span>
            </nav>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h4>Danh s&#225;ch kh&#243;a h&#7885;c</h4>
            <p class="text-muted">&#272;ang m&#7903;: {{ $activeCount }} &middot; T&#7855;t: {{ $inactiveCount }}</p>
            <a href="{{ route('course.create') }}" class="btn btn-primary m-r-5">Th&#234;m m&#7899;i</a>
            <form method="GET" action="{{ route('admin.course') }}" class="form-inline mt-3 mb-3">
                <label class="sr-only" for="admin-course-search">T&#236;m ti&#234;u &#273;&#7873;</label>
                <input id="admin-course-search" name="search" value="{{ $search }}" class="form-control mr-2" placeholder="T&#236;m theo ti&#234;u &#273;&#7873;">
                <label class="sr-only" for="admin-course-status">Tr&#7841;ng th&#225;i</label>
                <select id="admin-course-status" name="status" class="form-control mr-2">
                    <option value="">T&#7845;t c&#7843; tr&#7841;ng th&#225;i</option>
                    <option value="active" {{ $status === 'active' ? 'selected' : '' }}>&#272;ang m&#7903;</option>
                    <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>T&#7855;t</option>
                </select>
                <button class="btn btn-secondary" type="submit">L&#7885;c</button>
            </form>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Ti&#234;u &#273;&#7873;</th>
                            <th scope="col">Slug</th>
                            <th scope="col">N&#7893;i b&#7853;t</th>
                            <th scope="col">Hi&#7875;n th&#7883;</th>
                            <th scope="col">H&#224;nh &#273;&#7897;ng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($courses as $row)
                        <tr>
                            <th scope="row">{{ $row->title }}</th>
                            <td>{{ $row->slug }}</td>
                            <td>{{ $row->is_featured ? 'Có' : 'Không' }}</td>
                            <td><span class="badge {{ $row->is_active ? 'badge-success' : 'badge-secondary' }}">{{ $row->is_active ? '&#272;ang m&#7903;' : 'T&#7855;t' }}</span></td>
                            <td>
                                <a href="{{ route('course.preview', $row->id) }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary">Xem trước</a>
                                <a href="{{ route('course.edit', $row->id) }}">
                                    <button class="btn btn-icon btn-primary btn-rounded btn-tone">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </a>
                                <a href="{{ route('course.detail', $row->slug) }}" target="_blank" title="Xem trang public">
                                    <button class="btn btn-icon btn-info btn-rounded btn-tone">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </a>
                                <form action="{{ route('course.delete', $row->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Bạn có chắc muốn xóa? Khóa học đã có lead sẽ được ẩn thay vì xóa.')">
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
                {{ $courses->links('vendor.pagination') }}
            </div>
        </div>
    </div>
</div>
@endsection
