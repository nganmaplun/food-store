@extends('layout.admin-layout')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Duyệt checkin</h1>
            </div>
            <div class="col-sm-6">
                <h6 class="float-right">Ngày: {{$today}}</h6>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<section class="content">
    <!-- Default box -->
    <div class="card">
        <div class="card-body p-0">
            <table class="table table-striped projects">
                <thead>
                    <tr>
                        <th style="width: 40%">
                            Nhân viên
                        </th>
                        <th style="width: 20%">
                            Giờ checkin
                        </th>
                        <th style="width: 40%">
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($listTimesheet as $timesheet)
                    <tr index="{{$timesheet[\App\Constants\BaseConstant::ID_FIELD]}}">
                        <td>
                            {{$timesheet[\App\Constants\UserConstant::FULLNAME_FIELD]}}
                        </td>
                        <td>
                            {{$timesheet[\App\Constants\TimesheetConstant::CHECKIN_TIME_FIELD] ?? ''}}
                        </td>
                        @if(!$timesheet[\App\Constants\TimesheetConstant::IS_APPROVED_FIELD] &&
                        $timesheet[\App\Constants\BaseConstant::STATUS_FIELD])
                        <td class="project-actions text-right">
                            <a class="btn btn-primary btn-sm approved" href="javascript:void(0)">
                                <i class="fas fa-folder">
                                </i>
                                Duyệt
                            </a>
                            <a class="btn btn-danger btn-sm disapproved" href="javascript:void(0)">
                                <i class="fas fa-trash">
                                </i>
                                Hủy
                            </a>
                        </td>
                        @elseif (!$timesheet[\App\Constants\TimesheetConstant::IS_APPROVED_FIELD] &&
                        !$timesheet[\App\Constants\BaseConstant::STATUS_FIELD])
                        <td class="project-actions text-center">Đã hủy</td>
                        @else
                        <td class="project-actions text-center">Đã duyệt</td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
    <div class="card-footer">
        {{ $listTimesheet->links('vendor.pagination.bootstrap-4', ['page' => $page]) }}
    </div>
    <!-- /.card-footer -->
</section>
@endsection
@section('script')
<script>
    var urlApprove = "{{route('post.timesheet')}}";
</script>
<script src="{{asset('js/timesheet-page.js')}}"></script>
@endsection
