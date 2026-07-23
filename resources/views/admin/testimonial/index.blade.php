@extends('admin.layouts.master')
@section('content')
<div class="main-content">
    <div class="page-header">
        <div class="header-sub-title">
            <nav class="breadcrumb breadcrumb-dash">
                <a class="breadcrumb-item">Testimonial</a>
                <span class="breadcrumb-item active">Danh s&#225;ch</span>
            </nav>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h4>Danh s&#225;ch testimonial</h4>
            <a href="{{ route('testimonial.create') }}" class="btn btn-primary m-r-5">Th&#234;m m&#7899;i</a>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>T&#234;n</th>
                            <th>N&#7897;i dung</th>
                            <th>Rating</th>
                            <th>N&#7893;i b&#7853;t</th>
                            <th>Hi&#7875;n th&#7883;</th>
                            <th>H&#224;nh &#273;&#7897;ng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($testimonials as $row)
                        <tr>
                            <td>{{ $row->name }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($row->content, 80) }}</td>
                            <td>{{ $row->rating ?: '-' }}</td>
                            <td>{{ $row->is_featured ? 'Có' : 'Không' }}</td>
                            <td>{{ $row->is_active ? 'Có' : 'Không' }}</td>
                            <td>
                                <a href="{{ route('testimonial.edit', $row->id) }}">
                                    <button class="btn btn-icon btn-primary btn-rounded btn-tone"><i class="fas fa-edit"></i></button>
                                </a>
                                <form action="{{ route('testimonial.delete', $row->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Bạn có chắc muốn xóa?')">
                                    @csrf
                                    <button type="submit" class="btn btn-icon btn-danger btn-rounded btn-tone"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $testimonials->links('vendor.pagination') }}
            </div>
        </div>
    </div>
</div>
@endsection
