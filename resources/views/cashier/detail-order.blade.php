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
<form method="POST" action="{{ route('checkout', ['orderId' => $orderId]) }}">
    @csrf
    <section class="content pt-3">
        <div class="card">
            <div class="card-body p-0">
                <table class="table table-striped projects">
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
                                <span>
                                    {{ $food[\App\Constants\FoodConstant::VIETNAMESE_NAME_FIELD] }}
                                </span>
                            </td>
                            <td width="10%" class="custom-td">
                                <span>
                                    {{ $food[\App\Constants\FoodOrderConstant::ORDER_NUM_FIELD] }}
                                </span>
                            </td>
                            <td width="20%" class="custom-td">
                                <span>
                                    {{ number_format($food[\App\Constants\FoodConstant::PRICE_FIELD]) }}
                                </span>
                            </td>
                            <td width="30%" class="custom-td">
                                <span>
                                    {{ number_format($food[\App\Constants\FoodOrderConstant::ORDER_NUM_FIELD] *
                                    $food[\App\Constants\FoodConstant::PRICE_FIELD]) }}
                                </span>
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
                    <textarea class="form-control" aria-label="Ghi chú khác" name="other_note"></textarea>
                </div>
            </div>
            <div class="col-12 custom-control-inline pt-3" style="width: 100%">
                <div class="col-10 form-group row">
                    <label for="voucher" class="col-form-label">Giảm giá</label>
                    <select class="form-select offset-sm-1" id="voucher" name="voucher">
                        <option selected value="0">Chọn giảm giá</option>
                        <option value="5">5%</option>
                        <option value="10">10%</option>
                        <option value="15">15%</option>
                        <option value="20">20%</option>
                        <option value="25">25%</option>
                    </select>
                </div>
            </div>
            <div class="col-12">
                <button class="btn btn-info float-right bg-red" id="btn-paid">Thanh toán</button>
            </div>
        </div><!-- /.container-fluid -->
    </section>
</form>
@endsection
@section('script')
<script>
    var urlPaid = "{{ route('paid-order') }}";
    var orderId = "{{ $orderId }}";
    setTimeout(function () {
        $('.alert-success').css('display', 'none');
    }, 2000);
</script>
<script src="{{ asset('js/cashier-detail-order.js' )}}"></script>
@endsection
