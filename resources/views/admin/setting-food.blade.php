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
                    <h1>Thiết lập món</h1>
                </div>
                <div class="col-sm-6">
                    <button class="btn btn-info float-right" onclick="openModal(this)">Thiết lập món</button>
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
                            <h3 class="card-title">Danh sách món ngày : <strong id="today">{{ $today }}</strong></h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="listFoods" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Tên món</th>
                                    <th class="no-sort">Số lượng</th>
                                    <th class="no-sort"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($listFoods as $food)
                                <tr index="{{ $food[\App\Constants\BaseConstant::ID_FIELD] }}" fid="{{ $food['fid'] ?? '' }}">
                                    <td style="word-wrap: break-word">{{ $food[\App\Constants\FoodConstant::VIETNAMESE_NAME_FIELD] }}</td>
                                    <td>
                                        <input type="number" class="food-count" value="{{ $food[\App\Constants\FoodDayConstant::NUMBER_FIELD] ?? '' }}">
                                    </td>
                                    <td><button class="setting">Đặt</button></td>
                                </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>Tên món</th>
                                    <th>Số lượng</th>
                                    <th></th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
            </div>
        </div>
        @include('admin.modal-seting-food')
    </section>
@endsection

@section('script')
    <script>
        var setFoodUrl = "{{ route('admin.post.set-food') }}";
        var listAllFoodName = "{{ $listAllFoodName }}";
        var listAllName = JSON.parse(listAllFoodName.replace(/&quot;/g, '"'));
    </script>
    <script src="{{asset('js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('js/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{asset('js/setting-food.js')}}"></script>
@endsection
