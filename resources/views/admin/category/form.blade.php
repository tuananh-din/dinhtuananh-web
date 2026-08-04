@extends('admin.layouts.master')
@section('content')
<div class="main-content"><div class="card"><div class="card-body"><a href="{{ route('admin.category') }}" class="btn btn-primary m-b-15">Danh sách</a><form method="POST" action="{{ route('category.store') }}">@csrf @if(isset($category))<input type="hidden" name="id" value="{{ $category->id }}">@endif<div class="form-group"><label for="name">Tên</label><input id="name" class="form-control" name="name" value="{{ old('name', $category->name ?? '') }}" required></div><div class="form-group"><label for="slug">Slug</label><input id="slug" class="form-control" name="slug" value="{{ old('slug', $category->slug ?? '') }}"></div><button class="btn btn-success">Lưu</button></form></div></div></div>
@endsection
