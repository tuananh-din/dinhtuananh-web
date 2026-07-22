@extends('admin.layouts.master')
@section('content')
<div class="main-content">
    <div class="page-header no-gutters has-tab">
        <h2 class="font-weight-normal">Thông tin cá nhân</h2>
        <ul class="nav nav-tabs" >
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#tab-account">Cá nhân</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tab-network">MXH</a>
            </li>
            
        </ul>
    </div>
    <form class="forms-sample" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="form-validation" >
    @csrf
        <div class="container">
            <div class="tab-content m-t-15">
                <div class="tab-pane fade show active" id="tab-account" >
                    
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Thông tin cá nhân</h4>
                        </div>
                        <div class="card-body">
                            <div class="media align-items-center">
                                <div class="form-group col-md-6">
                                    <label class="font-weight-semibold">Ảnh đại diện</label><br>
                                    <img id="preview" src="{{ $about->avatar ?? 'app/assets/images/others/thumb-16.jpg' }}" alt="" height="80px">
                                    <div class="file-input">
                                        <input class="choose" type="file" name="avatar" accept="image/*">
                                        <span class="button">Thêm hình ảnh</span>
                                        <span class="label"></span>
                                    </div>
                                </div>
                            </div>
                            <hr class="m-v-25">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label class="font-weight-semibold" for="name">Họ tên:</label>
                                    <input name="name" type="text" class="form-control" id="name" value="{{$about->name}}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="font-weight-semibold" for="email">Email:</label>
                                    <input type="email" class="form-control" id="email" value="{{$about->email}}" name="email">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label class="font-weight-semibold" for="phoneNumber">Điện thoại:</label>
                                    <input type="text" class="form-control" id="phoneNumber" name="tel" value="{{$about->tel}}">
                                </div>
                                <div class="form-group col-md-8">
                                    <label class="font-weight-semibold" for="dob">Địa chỉ</label>
                                    <input type="text" class="form-control" id="dob" name="address" value="{{$about->address}}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Nội dung</h4>
                        </div>
                        <div class="card-body">
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label class="font-weight-semibold" for="fullAddress">Trang About me</label>
                                        <textarea id="about_me" name="about_me" class="form-control">{!!$about->about_me!!}</textarea>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label class="font-weight-semibold" for="description">Giới thiệu ngắn</label>
                                        <textarea id="description" rows="4" name="description" class="form-control">{{$about->description}}</textarea>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label class="font-weight-semibold" for="content">Timeline</label>
                                        <textarea id="content" name="content" class="form-control">{!!$about->content!!}</textarea>
                                    </div>
                                </div>
                        </div>
                    </div>
                    <div class="m-b-15">
                        <button class="btn btn-primary">
                            <i class="anticon anticon-save"></i>
                            <span>Lưu</span>
                        </button>
                    </div>
                </div>
                <div class="tab-pane fade" id="tab-network">
                    <div class="row">
                        <div class="col-md-8 mx-auto">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Mạng xã hội</h4>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item p-h-0">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-icon" style="color: #4267b1; background: rgba(66, 103, 177, 0.1)">
                                                        <i class="anticon anticon-facebook"></i>
                                                    </div>
                                                    <div class="font-size-15 font-weight-semibold m-l-15">Facebook</div>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <input class="form-control" type="text" name="facebook" value="{{$about->facebook}}">
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item p-h-0">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-icon" style="color: #fff; background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%,#d6249f 60%,#285AEB 90%)">
                                                        <i class="anticon anticon-instagram"></i>
                                                    </div>
                                                    <div class="font-size-15 font-weight-semibold m-l-15">Instagram</div>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <input class="form-control" type="text" name="instagram" value="{{$about->instagram}}">
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item p-h-0">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-icon" style="color: #1ca1f2; background: rgba(28, 161, 242, 0.1)">
                                                        <i class="anticon anticon-twitter"></i>
                                                        
                                                    </div>
                                                    <div class="font-size-15 font-weight-semibold m-l-15">Twitter</div>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <input class="form-control" type="text" name="x" value="{{$about->x}}">
                                                </div>
                                            </div>
                                        </li>
                                    
                                        <li class="list-group-item p-h-0">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-icon" style="color: #0174af; background: rgba(1, 116, 175, 0.1)">
                                                        <i class="anticon anticon-linkedin"></i>
                                                    </div>
                                                    <div class="font-size-15 font-weight-semibold m-l-15">Linkedin</div>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <input class="form-control" type="text" name="linkedin" value="{{$about->linkedin}}">
                                                    
                                                </div>
                                            </div>
                                        </li>
                                        
                                    </ul> 
                                    <div class="m-t-15">
                                        <button class="btn btn-primary">
                                            <i class="anticon anticon-save"></i>
                                            <span>Lưu</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>
<script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>
<script>
    let myEditor;
    ClassicEditor
    .create( document.querySelector( '#about_me' ),{
        
    })  
    .then( editor => {
        myEditor = editor;
    } )
    .catch( error => {
        console.error( error );
    } );

    ClassicEditor
    .create( document.querySelector( '#content' ),{
        
    })  
    .then( editor => {
        myEditor = editor;
    } )
    .catch( error => {
        console.error( error );
    } );

    // $( "#form-validation" ).validate({
    //     ignore: ':hidden:not(:checkbox)',
    //     errorElement: 'label',
    //     errorClass: 'is-invalid',
    //     validClass: 'is-valid',
    // });
</script>
@endsection