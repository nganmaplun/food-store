@extends('layout.no-menubar')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Các món ở quầy chờ</h1>
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
                            <th style="width: 20%">Bàn</th>
                            <th style="width: 40%">Món</th>
                            <th style="width: 20%">Số lượng</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($listFoods as $key => $food)
                        <tr>
                            <td>{{ $food[\App\Constants\TableConstant::NAME_FIELD] }}</td>
                            <td>{{ $food[\App\Constants\FoodConstant::VIETNAMESE_NAME_FIELD] }}</td>
                            <td>{{ $food[\App\Constants\FoodOrderConstant::ORDER_NUM_FIELD] }}</td>
                            <td><button class="btn btn-warning to-table"
                                    index="{{ $food[\App\Constants\BaseConstant::ID_FIELD] }}">Lên
                                    món</button></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')
<script>
    var urlFoodToTable = "{{ route('to-table-status') }}";
</script>
<script src="{{ asset('js/table-order.js' )}}"></script>
<script src="{{ asset('js/custom-waiter-only.js' )}}"></script>
@endsection
