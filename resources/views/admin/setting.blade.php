@extends('admin.layouts.master')
@section('content')

<div class="main-content">
    <div class="page-header">
        <h2 class="header-title">Cập nhật Thông tin</h2>
        <div class="header-sub-title">
            <nav class="breadcrumb breadcrumb-dash">
                <a class="breadcrumb-item">Cài đặt</a>
                <span class="breadcrumb-item active">Cập nhật</span>
            </nav>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h4>Cập nhật</h4>
            <p>Cập nhật Thông tin</p>
            <form class="forms-sample" action="{{ route('setting.update') }}" method="POST" enctype="multipart/form-data" id="form-validation">
                @csrf
                <div class="m-10">
                </div>
                <div class="m-t-25">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="font-weight-semibold" for="inputEmail4">Tên Website</label>
                            <input name="name" id="title_ipu" type="text" class="form-control" value="{{ $setting->name ?? null}}">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="font-weight-semibold" for="inputPassword4">Link Website</label>
                            <input name="url" id="link_ipu" type="text" class="form-control" value="{{ $setting->url ?? null}}">
                        </div>
                    </div>
                   
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="font-weight-semibold">Logo</label><br>
                            <img id="preview" src="{{ $setting->logo ?? 'app/assets/images/others/thumb-16.jpg' }}" alt="" height="80px">
                            <div class="file-input">
                                <input class="choose" type="file" name="logo" accept="image/*">
                                <span class="button">Thêm hình ảnh</span>
                                <span class="label"></span>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="font-weight-semibold">Favicon</label><br>
                            <img id="preview1" src="{{ $setting->favicon ?? 'app/assets/images/others/thumb-16.jpg' }}" alt="" height="80px">
                            <div class="file-input">
                                <input class="choose1" type="file" name="favicon" accept="image/*">
                                <span class="button">Thêm hình ảnh</span>
                                <span class="label1"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-semibold" for="productBrand">Code Header</label>
                        <textarea rows="6" class="form-control" id="productContent" name="code_header">{{ $setting->code_header ?? null}}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-semibold" for="productBrand">Code Body</label>
                        <textarea rows="6" class="form-control" id="productContent" name="code_body">{{ $setting->code_body ?? null}}</textarea>
                    </div>
               
                    <div class="form-group">
                        <label class="font-weight-semibold" for="productBrand">Slogan</label>
                        <textarea rows="6" class="form-control" id="productContent" name="slogan">{{ $setting->slogan ?? null}}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-semibold" for="productBrand">Note</label>
                        <textarea rows="6" class="form-control" id="productContent" name="note">{{ $setting->note ?? null}}</textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="font-weight-semibold" for="inputEmail4">Title</label>
                            <input name="title_seo" id="title_ipu" type="text" class="form-control" value="{{ $setting->title_seo ?? null}}">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="font-weight-semibold" for="inputPassword4">Keywords</label>
                            <input name="key_seo" id="link_ipu" type="text" class="form-control" value="{{ $setting->key_seo ?? null}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-semibold" for="productBrand">Description</label>
                        <textarea rows="6" class="form-control" id="productContent" name="desc_seo">{{ $setting->desc_seo ?? null}}</textarea>
                    </div>
                    <div class="m-b-15">
                        <button class="btn btn-primary">
                            <i class="anticon anticon-save"></i>
                            <span>Lưu</span>
                        </button>
                    </div>
                </div>
            <form>
        </div>
    </div>
</div>
<script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>
<script>
    let myEditor;
    ClassicEditor
    .create( document.querySelector( '#bank' ),{
        
    })  
    .then( editor => {
        myEditor = editor;
    } )
    .catch( error => {
        console.error( error );
    } );

    ClassicEditor
    .create( document.querySelector( '#description_product' ),{
        
    })  
    .then( editor => {
        myEditor = editor;
    } )
    .catch( error => {
        console.error( error );
    } );

    $( "#form-validation" ).validate({
        ignore: ':hidden:not(:checkbox)',
        errorElement: 'label',
        errorClass: 'is-invalid',
        validClass: 'is-valid',
    });
</script>
@endsection