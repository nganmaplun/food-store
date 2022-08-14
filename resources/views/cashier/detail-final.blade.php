<?php
use Carbon\Carbon;
?>

@extends($role == \App\Constants\BaseConstant::ADMIN_ROLE ? 'layout.admin-layout' : 'layout.no-menubar')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Chi tiết order cuối cùng</h1>
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
                                Nhân viên thu nhân : {{ $cashierName[$orderInfo[\App\Constants\OrderConstant::CASHIER_ID_FIELD]] }}<br/>
                                Ngày : {{ Carbon::now()->format('Y-m-d H:i:s') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="content pt-3">
        <div class="card">
            <div class="card-body p-0">
                <table class="table table-striped orderList">
                    <thead>
                    <tr>
                        <th style="width: 30%">Tên món</th>
                        <th style="width: 10%">Số lượng</th>
                        <th style="width: 20%">Thành tiền</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($detail as $food)
                        <tr>
                            <td width="30%" class="custom-td">
                                @if ($orderInfo[\App\Constants\OrderConstant::CUSTOMER_TYPE_FIELD] == "V")
                                    {{ $food[\App\Constants\FoodConstant::VIETNAMESE_NAME_FIELD] }}
                                @elseif($orderInfo[\App\Constants\OrderConstant::CUSTOMER_TYPE_FIELD] == "J")
                                    {{ $food[\App\Constants\FoodConstant::SHORT_NAME_FIELD] }}
                                @elseif($orderInfo[\App\Constants\OrderConstant::CUSTOMER_TYPE_FIELD] == "E")
                                    {{ $food[\App\Constants\FoodConstant::ENGLISH_NAME_FIELD] }}
                                @endif
                            </td>
                            <td width="10%" class="custom-td">
                                {{ $food[\App\Constants\FoodOrderConstant::ORDER_NUM_FIELD] }}
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
            <span>---------------------------------------------------------------------</span>
            <div class="col-12 custom-control-inline" style="width: 100%">
                <div class="col-10 form-group row">
                    <label class="col-form-label p-1" for="other-money">Tiền phụ thu : </label>
                    <span class="col-form-label p-1" id="other-money">{{ number_format($orderInfo[\App\Constants\OrderConstant::OTHER_MONEY_FIELD]) . 'VND' }}</span>
                </div>
            </div>
            <div class="col-12 custom-control-inline" style="width: 100%">
                <div class="col-10 form-group row">
                    <label class="col-form-label p-1" for="paid-type" >Phương thức thanh toán : </label>
                    <span class="col-form-label p-1" id="paid-type">{{ $orderInfo[\App\Constants\OrderConstant::PAID_TYPE_FIELD] == 'money' ? 'Tiền mặt' : 'Thẻ visa' }}</span>
                </div>
            </div>
            <span>---------------------------------------------------------------------</span>
            <div class="col-12 custom-control-inline" style="width: 100%">
                <div class="col-10 form-group row">
                    <label class="col-form-label p-1" for="tax">Thành tiền : </label>
                    <span class="col-form-label p-1" id="money">{{ number_format($orderInfo[\App\Constants\OrderConstant::TOTAL_PRICE_FIELD] / 0.95) . 'VND'}}</span>
                </div>
            </div>
            <div class="col-12 custom-control-inline" style="width: 100%">
                <div class="col-10 form-group row">
                    <label class="col-form-label p-1" for="tax">Thuế (8%) : </label>
                    <span class="col-form-label p-1" id="tax">{{ number_format($orderInfo[\App\Constants\OrderConstant::TOTAL_PRICE_FIELD] * 0.08) . 'VND'}}</span>
                </div>
            </div>
            <div class="col-12 custom-control-inline" style="width: 100%">
                <div class="col-10 form-group row">
                    <label for="voucher" class="col-form-label p-1">Giảm giá ({{ $orderInfo[\App\Constants\OrderConstant::DISCOUNT_FIELD] . '%' ?? '' }}) : </label>
                    <span class="col-form-label p-1" id="voucher">{{ number_format($orderInfo[\App\Constants\OrderConstant::TOTAL_PRICE_FIELD] / 0.95 - $orderInfo[\App\Constants\OrderConstant::TOTAL_PRICE_FIELD]) . 'VND' }}</span>
                </div>
            </div>
            <div class="col-12">
                <div class="col-10 form-group row">
                    <label class="col-form-label p-1" for="total_price">Tổng tiền : </label>
                    <span class="col-form-label p-1" id="total_price">{{ number_format($orderInfo[\App\Constants\OrderConstant::TOTAL_PRICE_FIELD] + $orderInfo[\App\Constants\OrderConstant::TOTAL_PRICE_FIELD] * 0.08) . 'VND' }}</span>
                </div>
            </div>
            <div class="col-12 row justify-content-end">
                <button class="btn btn-info" id="btn-paid">Lưu thông tin</button>
            </div>
        </div><!-- /.container-fluid -->
    </section>
@endsection
@section('script')
    <script>
        var urlPaid = "{{ route('update-final-cashier', ['orderId' => $orderId]) }}";
        setTimeout(function () {
            $('.alert-success').css('display', 'none');
        }, 2000);
        $(function () {
            $('#btn-paid').on('click', function() {
                $.ajax({
                    url: urlPaid,
                    type: "POST",
                    success:function (response) {
                        showMessage(1500, response.message);
                        setTimeout(function () {
                            location.href = domain + '/cashier/dashboard';
                        }, 2000)
                    }
                });
            })
        })
    </script>
@endsection
