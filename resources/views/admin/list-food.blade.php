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
                    <h1>Danh sách món ăn</h1>
                </div>
                <div class="col-6">
                    <div class="float-right">
                        <a class="btn btn-primary" href="{{ route('view-create-food') }}">Tạo món</a>
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
                            <table id="listFoods" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tên tiếng việt</th>
                                    <th>Tên tiếng nhật</th>
                                    <th>Tên khác</th>
                                    <th>Giá</th>
                                    <th>Danh mục</th>
                                    <th class="no-sort"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($listFood as $key => $food)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $food[\App\Constants\FoodConstant::VIETNAMESE_NAME_FIELD] }}</td>
                                        <td>{{ $food[\App\Constants\FoodConstant::JAPANESE_NAME_FIELD] }}</td>
                                        <td>{{ $food[\App\Constants\FoodConstant::ENGLISH_NAME_FIELD] }}</td>
                                        <td>{{ $food[\App\Constants\FoodConstant::PRICE_FIELD] }}</td>
                                        <td>{{ \App\Constants\BaseConstant::ARRAY_KITCHEN_ALL[$food[\App\Constants\FoodConstant::CATEGORY_FIELD]] }}</td><td>
                                            <a class="btn btn-info" href="{{ route('view-edit-food', ['id' => $food[\App\Constants\BaseConstant::ID_FIELD]]) }}">Sửa</a>
                                            <a class="btn btn-danger" onclick="confirmDelete(this)" index="{{ $food[\App\Constants\BaseConstant::ID_FIELD] }}" href="javascript:void(0)">Xóa</a>
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
        var url = "{{ route('delete-food', ['id' => 'index']) }}"
        $(function () {
            $('#listFoods').DataTable({
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
            if (confirm('Có muốn xóa món này?')) {
                let index = $(that).attr('index');
                url = url.replace('index', index);
                location.href = url;
            }
        }
    </script>
@endsection
