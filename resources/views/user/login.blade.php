<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Food Store | Log in</title>

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{asset('css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/adminlte.min.css')}}">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="card">
            <div class="card-body login-card-body">
                <h2 class="login-box-msg font-weight-bolder">Sign in</h2>
                @error('not_valid')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <form action="{{route('post.login')}}" method="post">
                    @csrf
                    <div class="col-12">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control @error('username') is-invalid @enderror"
                                placeholder="Username" name="username" value="{{old('username')}}">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @error('username')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <div class="col-12">
                        <div class="input-group mb-3">
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                placeholder=" Password" name="password">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @error('password')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <div class="row">
                        <div class="col-4 align-items-center">
                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
