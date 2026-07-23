@extends('admin.layouts.master')
@section('content')
<div class="main-content">
    <div class="page-header">
        <div class="header-sub-title">
            <nav class="breadcrumb breadcrumb-dash">
                <a class="breadcrumb-item">Lead</a>
                <span class="breadcrumb-item active">Danh s&#225;ch</span>
            </nav>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h4>Danh s&#225;ch lead &#273;&#259;ng k&#253;</h4>
            <div class="d-flex flex-wrap align-items-center justify-content-between m-b-15">
                <div class="lead-counts">
                    <span class="badge badge-primary m-r-5">Tổng {{ $total }}</span>
                    @foreach(\App\Models\Lead::STATUSES as $sValue => $sLabel)
                        <span class="badge badge-default m-r-5">{{ $sLabel }}: {{ $counts[$sValue] ?? 0 }}</span>
                    @endforeach
                </div>
                <form method="GET" action="{{ route('admin.lead') }}" class="lead-filter">
                    <select name="status" class="form-control" onchange="this.form.submit()">
                        <option value="">Tất cả trạng thái</option>
                        @foreach(\App\Models\Lead::STATUSES as $sValue => $sLabel)
                            <option value="{{ $sValue }}" {{ ($status ?? '') === $sValue ? 'selected' : '' }}>{{ $sLabel }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Kh&#225;ch h&#224;ng</th>
                            <th>Ngu&#7891;n</th>
                            <th>Tr&#7841;ng th&#225;i</th>
                            <th>Ghi ch&#250;</th>
                            <th>C&#7853;p nh&#7853;t</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($leads as $lead)
                        <tr>
                            <td>{{ $lead->id }}</td>
                            <td>
                                <strong>{{ $lead->name }}</strong><br>
                                S&#272;T: {{ $lead->phone }}<br>
                                @if($lead->email)Email: {{ $lead->email }}<br>@endif
                                @if($lead->message)N&#7897;i dung: {{ $lead->message }}@endif
                            </td>
                            <td>
                                {{ $lead->source_page ?: '-' }}<br>
                                @if($lead->course_id){{ $lead->course?->title ?? ('Course #'.$lead->course_id) }}@endif
                            </td>
                            <td>
                                <form action="{{ route('lead.update', $lead->id) }}" method="POST">
                                    @csrf
                                    <select name="status" class="form-control">
                                        @foreach(\App\Models\Lead::STATUSES as $value => $label)
                                            <option value="{{ $value }}" {{ $lead->status == $value ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                            </td>
                            <td>
                                    <input type="text" name="note" class="form-control" value="{{ $lead->note }}" placeholder="Ghi ch&#250;">
                            </td>
                            <td>
                                    <button class="btn btn-success btn-tone">L&#432;u</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $leads->links('vendor.pagination') }}
            </div>
        </div>
    </div>
</div>
@endsection
