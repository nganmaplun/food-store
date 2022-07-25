@extends($route === 'admin.view.order' ? 'layout.admin-layout' : 'layout.no-menubar')

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
                <h1>Danh sách món của : {{ $tableName[\App\Constants\TableConstant::NAME_FIELD] }}</h1>
            </div>
            @if ($route === 'view.order')
            <div class="col-sm-6 row justify-content-end">
                <a href="{{ route('food-list', ['tableId' => $tableId, 'orderId' => $orderId]) }}"
                    class="btn btn-info">Thêm món</a>
                <div class="pl-2"></div>
                <a href="javascript:void(0)" onclick="openModal(this)" class="btn btn-info">Sửa note</a>
            </div>
            @endif
        </div>
    </div><!-- /.container-fluid -->
</section>
@error('error')
<div class="alert alert-danger">{{ $message }}</div>
@enderror
<section class="content">
    <div class="container-fluid">
        <div class="card p-3">
            <h6>Ghi chú</h6>
            <small id="update-text">{{ $orderInfo[\App\Constants\OrderConstant::DESCRIPTION_FIELD] }}</small>
        </div>
        <div class="col-8 offset-2 pb-2">
            <select class="form-control" id="lang-change">
                <option selected disabled>Hiển thị</option>
                <option value="vie">Tiếng Việt</option>
                <option value="jap">Tiếng Nhật</option>
                <option value="eng">Tiếng Anh</option>
            </select>
        </div>
        <div class="card">
            <div class="card-body p-3">
                <table class="table table-bordered" id="list-order">
                    <thead>
                        <tr>
                            <th class="all">#</th>
                            <th class="all">Món</th>
                            <th class="all">Số lượng</th>
                            <th class="none">Trạng thái</th>
                            <th class="none">Giờ order</th>
                            <th class="none"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($listFoods as $key => $food)
                        <tr
                            class="{{ $food[\App\Constants\FoodOrderConstant::IS_DELIVERED_FIELD] == true ? 'bg-orange' : ($food[\App\Constants\FoodOrderConstant::IS_NEW_FIELD] == true ? 'bg-light-pink' : 'bg-white') }}">
                            <td class="">{{ $key + 1 }}</td>
                            <td><span style="font-size: larger; font-weight: bolder">{{ $food[\App\Constants\FoodConstant::SHORT_NAME_FIELD] }}<br/></span>
                                <span class="vie">{{ $food[\App\Constants\FoodConstant::VIETNAMESE_NAME_FIELD] }}<br/></span>
                                <span class="jap">{{ $food[\App\Constants\FoodConstant::JAPANESE_NAME_FIELD] }}<br/></span>
                                <span class="eng">{{ $food[\App\Constants\FoodConstant::ENGLISH_NAME_FIELD] }}<br/></span>
                                <small class="text-info" style="word-break: break-all">{{ $food[\App\Constants\FoodOrderConstant::NOTE_FIELD] ?? '' }}</small>
                            </td>
                            <td>{{ $food[\App\Constants\FoodOrderConstant::ORDER_NUM_FIELD] }}</td>
                            <td>{{ $food[\App\Constants\FoodOrderConstant::IS_DELIVERED_FIELD] == true ? 'Đã lên' :
                                ($food[\App\Constants\FoodOrderConstant::IS_COMPLETED_FIELD] == true ? 'Bếp xong' :
                                'Đang nấu') }}</td>
                            <td>{{ $food[\App\Constants\FoodOrderConstant::ORDER_TIME_FIELD] }}</td>
                            <td>
                                <button class="btn btn-warning to-table" {{ $food[\App\Constants\FoodOrderConstant::IS_COMPLETED_FIELD] == true ? ( $food[\App\Constants\FoodOrderConstant::IS_DELIVERED_FIELD] == true ? 'hidden' : '') : 'hidden' }} index="{{ $food[\App\Constants\BaseConstant::ID_FIELD] }}">Lên món</button>
                                <button class="btn btn-warning btn-delete" {{ $food[\App\Constants\FoodOrderConstant::IS_COMPLETED_FIELD]==true ? 'hidden' : ($food[\App\Constants\FoodOrderConstant::IS_SENT_FIELD]==true ? 'hidden' : '') }} index="{{ $food[\App\Constants\BaseConstant::ID_FIELD] }}">Xóa</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@include('waiter.modal-create-order')
@endsection

@section('script')
<script>
    var sendMessage = "{{ route('send-message') }}";
        var sendMessageDelete = "{{ route('remove-order-food') }}";
        var urlFoodToTable = "{{ route('to-table-status') }}";
        var orderId = {{ $orderId }};
        var tableId = {{ $tableId }};
        var orderInfo = "{{ json_encode($orderInfo->toArray()) }}";
</script>
<script src="{{asset('js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{ asset('js/table-order.js' )}}"></script>
<script src="{{ asset('js/custom-waiter-only.js' )}}"></script>
@endsection
