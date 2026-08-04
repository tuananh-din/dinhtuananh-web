<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Đăng nhập | {{ data_get($infor, 'name', 'Admin') }}</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('app/assets/images/logo/favicon.png') }}">

    <!-- page css -->

    <!-- Core css -->
    <link href="{{ asset('app/assets/css/app.min.css') }}" rel="stylesheet">
    <link href="{{ asset('app/assets/css/main.css') }}" rel="stylesheet">

</head>

<body>
    <div class="app">
        <div class="container-fluid p-h-0 p-v-20 bg full-height d-flex" style="background-image: url('{{ asset('app/assets/images/others/login-3.png') }}')">
            <div class="d-flex flex-column justify-content-between w-100">
                <div class="container d-flex h-100">
                    <div class="row align-items-center w-100">
                        <div class="col-md-7 col-lg-5 m-h-auto">
                            <div class="card shadow-lg">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between m-b-30">
                                        <img class="img-fluid" alt="{{ data_get($infor, 'name', 'Website') }}" src="{{ asset('app/assets/images/logo/logo.png') }}">
                                        <h2 class="m-b-0">{{ data_get($infor, 'name', 'Website') }} Admin</h2>
                                    </div>
                                    @if ($errors->any())
                                        <p style="color:red">{{ $errors->first() }}</p>
                                    @endif
                                    <form method="POST" action="{{ route('login.send') }}">
                                        @csrf
                                        <div class="form-group">
                                            <label class="font-weight-semibold" for="userName">Email:</label>
                                            <div class="input-affix">
                                                <i class="prefix-icon anticon anticon-user"></i>
                                                <input type="email" class="form-control" id="userName" name="email" value="{{ old('email') }}" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="font-weight-semibold" for="password">Password:</label>
                                            <div class="input-affix m-b-10">
                                                <i class="prefix-icon anticon anticon-lock"></i>
                                                <input type="password" class="form-control" id="password" name="password" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="d-flex align-items-center" for="remember"><input type="checkbox" name="remember" id="remember" value="1" class="m-r-5"> Ghi nhớ đăng nhập</label>
                                        </div>
                                        <div class="form-group">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <button type="submit" class="btn btn-primary">Đăng nhập</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Core Vendors JS -->
    <script src="{{ asset('app/assets/js/vendors.min.js') }}"></script>

    <!-- page js -->

    <!-- Core JS -->
    <script src="{{ asset('app/assets/js/app.min.js') }}"></script>

</body>

</html>
