@extends('admin.layouts.master')

@section('content')
<div class="main-content">
    <div class="page-header">
        <div class="header-sub-title">
            <nav class="breadcrumb breadcrumb-dash">
                <span class="breadcrumb-item active">Dashboard</span>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-lg-3">
            <div class="card"><div class="card-body"><p class="m-b-5">Bài viết</p><h2 class="m-b-0">{{ $blogCount }}</h2></div></div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card"><div class="card-body"><p class="m-b-5">Khóa học đang hiển thị</p><h2 class="m-b-0">{{ $activeCourseCount }}</h2></div></div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card"><div class="card-body"><p class="m-b-5">Leads</p><h2 class="m-b-0">{{ $leadCounts->sum() }}</h2></div></div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card"><div class="card-body"><p class="m-b-5">Testimonial</p><h2 class="m-b-0">{{ $testimonialCount }}</h2></div></div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-wrap align-items-center justify-content-between m-b-15">
                <h4 class="m-b-0">Lead mới nhất</h4>
                <a href="{{ route('admin.lead') }}" class="btn btn-primary btn-tone">Xem tất cả leads</a>
            </div>
            <div class="lead-counts m-b-15">
                @foreach(\App\Models\Lead::STATUSES as $status => $label)
                    <span class="badge badge-default m-r-5">{{ $label }}: {{ $leadCounts[$status] ?? 0 }}</span>
                @endforeach
            </div>
            @if($recentLeads->isEmpty())
                <p class="text-muted m-b-0">Chưa có lead nào.</p>
            @else
                <div class="table-responsive">
                    <table class="table">
                        <thead><tr><th>Khách hàng</th><th>Khóa học</th><th>Trạng thái</th><th>Thời gian</th></tr></thead>
                        <tbody>
                            @foreach($recentLeads as $lead)
                                <tr>
                                    <td><strong>{{ $lead->name }}</strong><br>{{ $lead->phone }}</td>
                                    <td>{{ $lead->course?->title ?? '-' }}</td>
                                    <td>{{ \App\Models\Lead::STATUSES[$lead->status] ?? $lead->status }}</td>
                                    <td>{{ optional($lead->created_at)->format('d/m/Y H:i') ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
