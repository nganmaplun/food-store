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
                <div class="col-sm-6">
                    <h1>Thống kê doanh thu</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <label for="agg-type" class="col-sm-1 col-form-label d-none d-sm-block">Thống kê: </label>
                                <div class="col-12 col-sm-5">
                                    <select id="agg-type" class="form-control">
                                        <option {{ $type === '' ? 'selected' : '' }} value="">Chọn kiểu thống kê</option>
                                        <option {{ $type === 'day' ? 'selected' : '' }} value="day">Ngày</option>
                                        <option {{ $type === 'month' ? 'selected' : '' }} value="month">Tháng</option>
                                        <option {{ $type === 'year' ? 'selected' : '' }} value="year">Năm</option>
                                        <option value="custom">Khoảng</option>
                                    </select>
                                </div>
                            </div>
                            <div class="pt-1" hidden id="custom-date">
                                <div class="row">
                                    <label for="from-sm" class="col-sm-1 col-form-label">From: </label>
                                    <div class="col-12 col-sm-3">
                                        <input id="from-sm" class="form-control" type="text" name="min">
                                    </div>
                                    <label for="to-sm" class="col-sm-1 col-form-label">To: </label>
                                    <div class="col-12 col-sm-3">
                                        <input id="to-sm" class="form-control" type="text" name="max">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="aggOrders" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>STT</th>
                                    <th class="no-sort">Khách Việt</th>
                                    <th class="no-sort">Khách Nhật</th>
                                    <th class="no-sort">Khách Khác</th>
                                    <th class="no-sort">Số lượng món</th>
                                    <th class="no-sort">Thành tiền</th>
                                    <th>{{ $type == 'day' ? 'Ngày' : ($type == 'month' ? 'Tháng' : ($type == 'year' ? 'Năm' : 'Ngày')) }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($results as $key => $result)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $result['vietnamese_guest'] ?? 0 }}</td>
                                        <td>{{ $result['japanese_guest'] ?? 0 }}</td>
                                        <td>{{ $result['other_guest'] ?? 0 }}</td>
                                        <td>{{ $result['total_food'] ?? 0 }}</td>
                                        <td>{{ $result['total_price'] ?? 0 }}</td>
                                        <td>{{ $result['order_date'] ?? '' }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>STT</th>
                                    <th class="no-sort">Khách Việt</th>
                                    <th class="no-sort">Khách Nhật</th>
                                    <th class="no-sort">Khách Khác</th>
                                    <th class="no-sort">Số lượng món</th>
                                    <th class="no-sort">Thành tiền</th>
                                    <th>{{ $type == 'day' ? 'Ngày' : ($type == 'month' ? 'Tháng' : ($type == 'year' ? 'Năm' : 'Ngày')) }}</th>
                                </tr>
                                </tfoot>
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
    <script>
        var baseUrl = "{{ route('admin.aggOrder') }}";
        var optionUrl = "{{ route('admin.aggOrder', ["type" => "cus-type"]) }}";
        var ocustomUrl = "{{ route('aggOrder-custom') }}";
    </script>
    <script src="{{asset('js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('js/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{asset('js/aggregation.js')}}"></script>
@endsection
