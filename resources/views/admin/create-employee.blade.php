@extends('layout.admin-layout')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-6">
                    <h1>Chi tiết nhân viên</h1>
                </div>
                <div class="col-6">
                    <div class="float-right">
                        <a class="btn btn-primary" href="{{ route('list-employee') }}">Trở lại</a>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    @if (\Session::has('status'))
        <div class="alert alert-success">
            <span>{!! \Session::get('status') !!}</span>
        </div>
    @endif
    <section class="content">
        <form method="POST" action="{{ route('create-employee') }}">
            @csrf
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="inputName">Tài khoản đăng nhập</label>
                                <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}">
                            </div>
                            @error('username')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <div class="form-group">
                                <label for="inputName">Tên đầy đủ</label>
                                <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name') }}">
                            </div>
                            @error('full_name')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <div class="form-group">
                                <label for="inputName">Mật khẩu</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" value="">
                            </div>
                            @error('password')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <div class="form-group">
                                <label for="inputName">Điện thoại</label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}">
                            </div>
                            @error('phone')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <div class="form-group">
                                <label for="inputStatus">Role</label>
                                <select id="inputStatus" name="role" class="form-control custom-select @error('role') is-invalid @enderror">
                                    <option disabled selected>Select one</option>
                                    <option value="admin">Admin</option>
                                    <option value="cashier">Thu ngân</option>
                                    <option value="waiter/waitress">Chạy bàn</option>
                                    <option value="chef_salad">Bếp đồ sống (SASHI)</option>
                                    <option value="chef_steam">Bếp ga(KON)</option>
                                    <option value="chef_grill">Bếp chiên (AGE)</option>
                                    <option value="chef_drying">Bếp nướng (YAKI)</option>
                                    <option value="chef_drink">Đồ uống</option>
                                </select>
                            </div>
                            @error('role')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <a href="{{ route('list-employee') }}" class="btn btn-secondary">Hủy bỏ</a>
                    <input type="submit" value="Tạo mới" class="btn btn-success float-right">
                </div>
            </div>
        </form>
    </section>
@endsection
