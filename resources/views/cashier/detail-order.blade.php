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
    <section class="content pt-3">
        <div class="card">
            <div class="card-body p-0">
                <table class="table table-striped projects">
                    <thead>
                        <tr>
                            <th style="width: 40%">Tên món</th>
                            <th style="width: 20%">Số lượng</th>
                            <th style="width: 30%">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($detail as $food)
                        <tr>
                            <td width="40%" class="custom-td">
                                <span>
                                    {{ $food[\App\Constants\FoodConstant::VIETNAMESE_NAME_FIELD] }}
                                </span>
                            </td>
                            <td width="40%" class="custom-td">
                                <span>
                                    {{ $food[\App\Constants\FoodOrderConstant::ORDER_NUM_FIELD] }}
                                </span>
                            </td>
                            <td width="40%" class="custom-td">
                                <span>
                                    {{ number_format($food[\App\Constants\FoodOrderConstant::ORDER_NUM_FIELD] *  $food[\App\Constants\FoodConstant::PRICE_FIELD]) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        @include('waiter.modal-food-note')
    </section>
    <section class="custom-footer">
        <div class="container-fluid">
            <div class="mb-2 custom-control-inline" style="width: 100%">
                <div class="col-12">
                    <div class="form-group custom-control-inline">
                        <label for="voucher" class="col-form-label">Voucher</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="voucher">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <button class="btn btn-info float-right bg-red" i>Thanh toán</button>
            </div>
        </div><!-- /.container-fluid -->
    </section>
@endsection
