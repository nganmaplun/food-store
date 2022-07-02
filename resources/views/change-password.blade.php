@extends('layout.no-menubar')

@section('content')
    <div class="hold-transition login-page">
        <div class="login-box">
            <div class="card card-outline card-primary">
                <h2 class="login-box-msg font-weight-bolder pt-5">Đổi mật khẩu</h2>
                @if (Session::has('message'))
                <div class="alert alert-info">{{ Session::get('message') }}</div>
                @endif
                <div class="card-body">
                <form action="{{ route('post.change-password') }}" method="post">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="password" class="form-control @error('old_password') is-invalid @enderror" name="old_password" placeholder="Mật khẩu hiện tại">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    @error('old_password')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <div class="input-group mb-3">
                        <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Mật khẩu mới">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    @error('password')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <div class="input-group mb-3">
                        <input type="password" class="form-control @error('password_confirm') is-invalid @enderror" name="password_confirm" placeholder="Xác nhận mật khẩu mới">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    @error('password_confirm')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <div class="col-12 custom-control-inline">
                        <div class="col-5"><a href="{{ route('view.login') }}" class="btn btn-primary btn-block">Trở lại</a></div>
                        <div class="col-5 offset-1"><button type="submit" class="btn btn-primary btn-block">Xác nhận</button></div>
                    </div>
                        <!-- /.col -->
                </form>
            </div>
            <!-- /.login-card-body -->
            </div>
        </div>
    </div>
@endsection
