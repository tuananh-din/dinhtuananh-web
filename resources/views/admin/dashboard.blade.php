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
            <div class="card"><div class="card-body"><p class="m-b-5">Leads</p><h2 class="m-b-0">{{ $totalLeads }}</h2><small>7 ngày: {{ $leadsLast7Days }} · 30 ngày: {{ $leadsLast30Days }}</small></div></div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card"><div class="card-body"><p class="m-b-5">Testimonial</p><h2 class="m-b-0">{{ $testimonialCount }}</h2></div></div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card"><div class="card-body"><p class="m-b-5">Đăng ký email</p><h2 class="m-b-0">{{ $subscriberCount }}</h2></div></div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card"><div class="card-body">
                <h4>Lead theo nguồn</h4>
                @forelse($leadsBySource as $item)
                    <div class="d-flex justify-content-between border-bottom py-2"><span>{{ $item->source }}</span><strong>{{ $item->total }}</strong></div>
                @empty
                    <p class="text-muted m-b-0">Chưa có dữ liệu nguồn lead.</p>
                @endforelse
            </div></div>
        </div>
        <div class="col-lg-6">
            <div class="card"><div class="card-body">
                <h4>Tỷ lệ theo trạng thái</h4>
                @foreach(\App\Models\Lead::STATUSES as $status => $label)
                    @php($count = $leadCounts[$status] ?? 0)
                    <div class="d-flex justify-content-between border-bottom py-2"><span>{{ $label }}</span><strong>{{ $count }} ({{ $totalLeads ? number_format($count * 100 / $totalLeads, 1) : 0 }}%)</strong></div>
                @endforeach
            </div></div>
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
