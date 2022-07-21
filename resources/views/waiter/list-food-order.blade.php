@extends('layout.layout')
<style>
    .custom-td {
        padding: 0.125rem !important;
    }
</style>
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ $tableName[\App\Constants\TableConstant::NAME_FIELD] }}</h1>
                </div>
                <div class="offset-md-3 col-sm-3">
                    <a class="btn btn-default float-right" href="{{ route('view.order', ['tableId' => $tableId, 'orderId' => $orderId]) }}">Xem món đã order</a>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <form method="GET">
                        <div class="input-group">
                            <input type="search" class="form-control" name="food_name" placeholder="Điền tên món ở đây" value="{{ $foodName ?? '' }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section class="content pt-3">
        <div class="card">
            <div class="card-body p-0">
                <table class="table table-striped projects">
                    <tbody>
                    @foreach($listFoods as $food)
                    <tr class="search-for-index-{{ $food[\App\Constants\BaseConstant::ID_FIELD] }}">
                        <td width="20%" class="custom-td">
                            <div class="col-12">
                                <img src="{{ asset('image/logo.png') }}" class="product-image custom-img" alt="Product Image">
                            </div>
                        </td>
                        <td width="40%" class="custom-td">
                            <a>
                                {{ $food[\App\Constants\FoodConstant::VIETNAMESE_NAME_FIELD] }}<br/>
                                {{ $food[\App\Constants\FoodConstant::JAPANESE_NAME_FIELD] }}<br/>
                                {{ $food[\App\Constants\FoodConstant::SHORT_NAME_FIELD] }}
                            </a>
                            <br/>
                            <small>
                                {{ $food[\App\Constants\FoodConstant::DESCRIPTION_FIELD] }}
                            </small>
                            <br/>
                            <a class="text-sm" onclick="openModal(this)" index="{{ $food[\App\Constants\BaseConstant::ID_FIELD] }}" href="javascript:void(0)">
                                Ghi chú
                            </a>
                        </td>
                        <td class="text-right custom-td" width="40%">
                            <ul class="list-inline">
                                <li class="list-inline-item minus-less">
                                    <button class="btn btn-primary btn-sm">
                                        -
                                    </button>
                                </li>
                                <li class="list-inline-item order-value">1</li>
                                <li class="list-inline-item add-more">
                                    <button class="btn btn-danger btn-sm">
                                        +
                                    </button>
                                </li>
                                <li class="list-inline-item btn-order" index="{{ $food[\App\Constants\BaseConstant::ID_FIELD] }}">
                                    <button class="btn btn-danger btn-sm">
                                        <i class="fas fa-cart-plus">
                                        </i>
                                    </button>
                                </li>
                                <li class="d-none hidden-note"></li>
                            </ul>
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
@endsection

@section('script')
    <script>
        var addToOrderUrl = "{{ route('add-food') }}";
        var tableId = "{{ $tableId }}";
        var orderId = "{{ $orderId }}";
    </script>
    <script src="{{ asset('js/list-food-order.js') }}"></script>
    <script src="{{ asset('js/custom-waiter-only.js') }}"></script>
@endsection
