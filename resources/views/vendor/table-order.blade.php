@extends('layout.no-menubar')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Danh sách món của : {{ $tableName[\App\Constants\TableConstant::NAME_FIELD] }}</h1>
            </div>
            <div class="col-sm-6 row justify-content-end">
                <a href="{{ route('food-list', ['tableId' => $tableId, 'orderId' => $orderId]) }}"
                    class="btn btn-info">Thêm món</a>
                <div class="pl-2"></div>
                <a href="javascript:void(0)" onclick="openModal(this)" class="btn btn-info">Sửa note</a>
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
            <h6>Ghi chú</h6>
            <small id="update-text">{{ $orderInfo[\App\Constants\OrderConstant::DESCRIPTION_FIELD] }}</small>
        </div>
        <div class="card">
            <div class="card-body p-0">
                <table class="table table-sm" id="list-order">
                    <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th style="width: 30%">Món</th>
                            <th style="width: 20%">Số lượng</th>
                            <th style="width: 20%">Trạng thái</th>
                            <th style="width: 20%">Giờ order</th>
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
                                ($food[\App\Constants\FoodOrderConstant::IS_COMPLETED_FIELD] == true ? 'Bếp xong' :
                                'Đang nấu') }}</td>
                            <td>{{ $food[\App\Constants\FoodOrderConstant::ORDER_TIME_FIELD] }}</td>
                            <td><button class="btn btn-warning btn-delete" {{
                                    $food[\App\Constants\FoodOrderConstant::IS_COMPLETED_FIELD]==true ? 'hidden' : '' }}
                                    index="{{ $food[\App\Constants\BaseConstant::ID_FIELD] }}">Xóa</button></td>
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
<script src="{{ asset('js/table-order.js' )}}"></script>
<script src="{{ asset('js/custom-waiter-only.js' )}}"></script>
@endsection
