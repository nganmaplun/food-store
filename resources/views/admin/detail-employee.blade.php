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
        <form method="POST" action="{{ route('edit-employee', ['id' => $employee[\App\Constants\BaseConstant::ID_FIELD]]) }}">
            @csrf
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="inputName">Tên đầy đủ</label>
                                <input type="text" name="full_name" class="form-control" value="{{ $employee[\App\Constants\UserConstant::FULLNAME_FIELD] }}">
                            </div>
                            <div class="form-group">
                                <label for="inputName">Mật khẩu</label>
                                <input type="password" name="password" class="form-control" value="">
                            </div>
                            <div class="form-group">
                                <label for="inputName">Điện thoại</label>
                                <input type="text" name="phone" class="form-control" value="{{ $employee[\App\Constants\UserConstant::PHONE_FIELD] }}">
                            </div>
                            <div class="form-group">
                                <label for="inputStatus">Role</label>
                                <select id="inputStatus" name="role" class="form-control custom-select">
                                    <option disabled selected>Select one</option>
                                    <option {{ $employee[\App\Constants\UserConstant::ROLE_FIELD] == 'admin' ? 'selected' : '' }} value="admin">Admin</option>
                                    <option {{ $employee[\App\Constants\UserConstant::ROLE_FIELD] == 'cashier' ? 'selected' : '' }} value="cashier">Thu ngân</option>
                                    <option {{ $employee[\App\Constants\UserConstant::ROLE_FIELD] == 'waiter/waitress' ? 'selected' : '' }} value="waiter/waitress">Chạy bàn</option>
                                    <option {{ $employee[\App\Constants\UserConstant::ROLE_FIELD] == 'chef_salad' ? 'selected' : '' }} value="chef_salad">Bếp đồ sống (SASHI)</option>
                                    <option {{ $employee[\App\Constants\UserConstant::ROLE_FIELD] == 'chef_steam' ? 'selected' : '' }} value="chef_steam">Bếp ga(KON)</option>
                                    <option {{ $employee[\App\Constants\UserConstant::ROLE_FIELD] == 'chef_grill' ? 'selected' : '' }} value="chef_grill">Bếp chiên (AGE)</option>
                                    <option {{ $employee[\App\Constants\UserConstant::ROLE_FIELD] == 'chef_drying' ? 'selected' : '' }} value="chef_drying">Bếp nướng (YAKI)</option>
                                    <option {{ $employee[\App\Constants\UserConstant::ROLE_FIELD] == 'chef_drink' ? 'selected' : '' }} value="chef_drink">Đồ uống</option>
                                </select>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <a href="{{ route('list-employee') }}" class="btn btn-secondary">Hủy bỏ</a>
                    <input type="submit" value="Thay đổi" class="btn btn-success float-right">
                </div>
            </div>
        </form>
    </section>
@endsection
