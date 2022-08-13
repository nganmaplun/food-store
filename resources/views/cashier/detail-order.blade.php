<?php
use Carbon\Carbon;
?>

@extends('layout.no-menubar')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Chi tiết order</h1>
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
                    <div class="card-header">
                        <div class="row">
                            Bàn : {{ $orderInfo[\App\Constants\TableConstant::NAME_FIELD] }}<br/>
                            Nhân viên phục vụ : {{ $orderInfo[\App\Constants\UserConstant::FULLNAME_FIELD] }}<br/>
                            Nhân viên thu nhân : {{ \Auth::user()[\App\Constants\UserConstant::FULLNAME_FIELD] }}<br/>
                            Ngày : {{ Carbon::now()->format('Y-m-d H:i:s') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<form method="POST" action="{{ $route === 'waiter.detail-order' ? route('update-final', ['orderId' => $orderId]) : route('checkout', ['orderId' => $orderId]) }}">
    @csrf
    <section class="content pt-3">
        <div class="card">
            <div class="card-body p-0">
                <table class="table table-striped orderList">
                    <thead>
                        <tr>
                            <th style="width: 30%">Tên món</th>
                            <th style="width: 10%">Số lượng</th>
                            <th style="width: 20%">Đơn giá</th>
                            <th style="width: 20%">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($detail as $food)
                        <tr>
                            <td width="30%" class="custom-td">
                                {{ $food[\App\Constants\FoodConstant::VIETNAMESE_NAME_FIELD] }}
                            </td>
                            <td width="10%" class="custom-td">
                                {{ $food[\App\Constants\FoodOrderConstant::ORDER_NUM_FIELD] }}
                            </td>
                            <td width="20%" class="custom-td">
                                {{ number_format($food[\App\Constants\FoodConstant::PRICE_FIELD]) }}
                            </td>
                            <td width="30%" class="custom-td price">
                                {{ number_format($food[\App\Constants\FoodOrderConstant::ORDER_NUM_FIELD] *
                                    $food[\App\Constants\FoodConstant::PRICE_FIELD]) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
    </section>
    <section class="custom-footer">
        <div class="container-fluid">
            <div class="col-12">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Ghi chú khác</span>
                    </div>
                    <textarea class="form-control" aria-label="Ghi chú khác" name="other_note" {{ $route === 'waiter.detail-order' ? 'disabled' : '' }}>{{ $orderInfo[\App\Constants\OrderConstant::DESCRIPTION_FIELD] }}</textarea>
                </div>
            </div>
            <div class="col-12 pt-3" {{ $route === 'waiter.detail-order' ? 'hidden' : '' }}>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Nhập số tiền phụ thu</span>
                    </div>
                    <input type="number" class="form-control" name="other_money" id="other-money" value="{{ $orderInfo[\App\Constants\OrderConstant::OTHER_MONEY_FIELD] ?? '' }}">
                </div>
            </div>
            <div class="col-12 custom-control-inline pt-3" style="width: 100%">
                <div class="col-10 form-group row">
                    <label for="voucher" class="col-form-label p-1">Giảm giá : </label>
                    <select class="form-select offset-sm-1" id="voucher" name="voucher" {{ $route === 'waiter.detail-order' ? 'disabled' : '' }}>
                        <option {{ $orderInfo[\App\Constants\OrderConstant::DISCOUNT_FIELD] == 0 ? 'selected' : '' }} value="0">Chọn giảm giá</option>
                        <option {{ $orderInfo[\App\Constants\OrderConstant::DISCOUNT_FIELD] == 5 ? 'selected' : '' }} value="5">5%</option>
                        <option {{ $orderInfo[\App\Constants\OrderConstant::DISCOUNT_FIELD] == 10 ? 'selected' : '' }} value="10">10%</option>
                        <option {{ $orderInfo[\App\Constants\OrderConstant::DISCOUNT_FIELD] == 15 ? 'selected' : '' }} value="15">15%</option>
                        <option {{ $orderInfo[\App\Constants\OrderConstant::DISCOUNT_FIELD] == 20 ? 'selected' : '' }} value="20">20%</option>
                        <option {{ $orderInfo[\App\Constants\OrderConstant::DISCOUNT_FIELD] == 25 ? 'selected' : '' }} value="25">25%</option>
                    </select>
                </div>
            </div>
            <div class="col-12 custom-control-inline" style="width: 100%">
                <div class="col-10 form-group row">
                    <label for="paid-type" class="col-form-label p-1">Phương thức thanh toán : </label>
                    <select class="form-select offset-sm-1" id="paid-type" name="paid_type"  {{ $route === 'waiter.detail-order' ? 'disabled' : '' }}>
                        <option selected disabled value="0">Chọn</option>
                        <option {{ $orderInfo[\App\Constants\OrderConstant::PAID_TYPE_FIELD] == 'money' ? 'selected' : '' }} value="money">Tiền mặt</option>
                        <option {{ $orderInfo[\App\Constants\OrderConstant::PAID_TYPE_FIELD] == 'card' ? 'selected' : '' }} value="card">Thẻ visa</option>
                    </select>
                </div>
            </div>
            <div class="col-12">
                <div class="col-10 form-group row">
                    <label class="col-form-label" for="draf_money">Tổng tiền tạm tính: </label>
                    <span class="col-form-label pl-1" id="draf_money">{{ $orderInfo[\App\Constants\OrderConstant::TOTAL_PRICE_FIELD] ?? '' }}</span>
                </div>
            </div>
            <div class="col-12">
                <button class="btn btn-info float-right bg-red" id="btn-paid">{{ $route === 'waiter.detail-order' ? 'Xác nhận' : 'Thanh toán' }}</button>
            </div>
        </div><!-- /.container-fluid -->
    </section>
</form>
@endsection
@section('script')
<script>
    var urlPaid = "{{ route('paid-order') }}";
    var orderId = "{{ $orderId }}";
    var currentPrice = {{ $orderInfo[\App\Constants\OrderConstant::TOTAL_PRICE_FIELD] ?? 0 }}
    setTimeout(function () {
        $('.alert-success').css('display', 'none');
    }, 2000);
</script>
<script src="{{ asset('js/cashier-detail-order.js' )}}"></script>
@endsection
