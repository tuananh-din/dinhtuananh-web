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
            <a href="{{ route('course.create') }}" class="btn btn-primary m-r-5">Th&#234;m m&#7899;i</a>
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
                            <td>{{ $row->is_featured ? 'C&#243;' : 'Kh&#244;ng' }}</td>
                            <td>{{ $row->is_active ? 'C&#243;' : 'Kh&#244;ng' }}</td>
                            <td>
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
                                <form action="{{ route('course.delete', $row->id) }}" method="POST" style="display:inline" onsubmit="return confirm('B&#7841;n c&#243; ch&#7855;c mu&#7889;n x&#243;a?')">
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
