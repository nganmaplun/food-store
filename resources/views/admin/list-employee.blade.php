@extends('layout.admin-layout')
@section('other-css')
    <link rel="stylesheet" href="{{asset('css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/jquery-ui.min.css')}}">
@endsection
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-6">
                    <h1>Danh sách nhân viên</h1>
                </div>
                <div class="col-6">
                    <div class="float-right">
                        <a class="btn btn-primary" href="{{ route('view-create-employee') }}">Tạo nhân viên</a>
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
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="listEmployee" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tên đầy đủ</th>
                                    <th class="no-sort">Số điện thoại</th>
                                    <th class="no-sort">Role</th>
                                    <th>Trạng thái</th>
                                    <th class="no-sort"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($listEmployee as $key => $employee)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $employee[\App\Constants\UserConstant::FULLNAME_FIELD] }}</td>
                                        <td>{{ $employee[\App\Constants\UserConstant::PHONE_FIELD] }}</td>
                                        <td>{{ $employee[\App\Constants\UserConstant::ROLE_FIELD] }}</td>
                                        <td>{{ $employee[\App\Constants\BaseConstant::STATUS_FIELD] == true ? 'active' : 'deactive' }}</td>
                                        <td>
                                            <a class="btn btn-info" href="{{ route('view-edit-employee', ['id' => $employee[\App\Constants\BaseConstant::ID_FIELD]]) }}">Sửa</a>
                                            <a class="btn btn-danger" onclick="confirmDelete(this)" index="{{ $employee[\App\Constants\BaseConstant::ID_FIELD] }}" href="javascript:void(0)">Xóa</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script src="{{asset('js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('js/responsive.bootstrap4.min.js')}}"></script>
    <script>
        var url = "{{ route('delete-employee', ['id' => 'index']) }}"
        $(function () {
            $('#listEmployee').DataTable({
                paging: true,
                lengthChange: true,
                searching: true,
                ordering: true,
                info: false,
                autoWidth: false,
                responsive: true,
                columnDefs: [{
                    orderable: false,
                    targets: "no-sort"
                }],
                lengthMenu: [5, 10, 20, 50, 100],
            });
        })
        function confirmDelete(that) {
            if (confirm('Có muốn xóa nhân viên này?')) {
                let index = $(that).attr('index');
                url = url.replace('index', index);
                location.href = url;
            }
        }
    </script>
@endsection
