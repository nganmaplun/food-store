@extends('layout.no-menubar')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Danh sách món của : {{ $tableName[\App\Constants\TableConstant::NAME_FIELD] }}</h1>
            </div>
            <div class="col-sm-6">
                <a href="{{ route('food-list', ['tableId' => $tableId, 'orderId' => $orderId]) }}"
                    class="btn btn-info float-right">Thêm món</a>
                <a href="{{ route('food-list', ['tableId' => $tableId, 'orderId' => $orderId]) }}"
                   class="btn btn-info float-right">Sửa note</a>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
@error('error')
<div class="alert alert-danger">{{ $message }}</div>
@enderror
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body p-0">
                <table class="table table-sm" id="list-order">
                    <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th style="width: 30%">Món</th>
                            <th style="width: 20%">Số lượng</th>
                            <th style="width: 20%">Trạng thái</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($listFoods as $key => $food)
                        <tr
                            class="{{ $food[\App\Constants\FoodOrderConstant::IS_DELIVERED_FIELD]==true ? 'bg-orange' : 'bg-white' }}">
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $food[\App\Constants\FoodConstant::VIETNAMESE_NAME_FIELD] }}</td>
                            <td>{{ $food[\App\Constants\FoodOrderConstant::ORDER_NUM_FIELD] }}</td>
                            <td>{{ $food[\App\Constants\FoodOrderConstant::IS_DELIVERED_FIELD] == true ? 'Đã lên' :
                                ($food[\App\Constants\FoodOrderConstant::IS_COMPLETED_FIELD] == true ? 'Bếp xong' : 'Đang nấu') }}</td>
                            <td><button class="btn btn-warning to-table" {{ $food[\App\Constants\FoodOrderConstant::IS_COMPLETED_FIELD] == true ? ( $food[\App\Constants\FoodOrderConstant::IS_DELIVERED_FIELD] == true ? 'hidden' : '') : 'hidden' }} index="{{ $food[\App\Constants\BaseConstant::ID_FIELD] }}">Lên món</button></td>
                            <td><button class="btn btn-warning btn-delete" {{ $food[\App\Constants\FoodOrderConstant::IS_COMPLETED_FIELD] == true ? 'hidden' : '' }} index="{{ $food[\App\Constants\BaseConstant::ID_FIELD] }}">Xóa</button></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<section class="custom-footer">
    <div class="container-fluid">
        <div class="mb-2 custom-control-inline" style="width: 100%">
            <div class="col-5">
                <button class="btn btn-info bg-yellow" id="send-chef">Gửi bếp</button>
            </div>
            <div class="offset-2 col-5">
                <button class="btn btn-info float-right bg-red" id="send-cashier">Thanh toán</button>
            </div>
        </div>
    </div><!-- /.container-fluid -->
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
    </script>
    <script src="{{ asset('js/table-order.js' )}}"></script>
    <script src="{{ asset('js/custom-waiter-only.js' )}}"></script>
@endsection
