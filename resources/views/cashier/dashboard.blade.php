@extends('layout.no-menubar')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Danh sách order thanh toán</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content pt-3">
        <div class="card">
            <div class="card-body p-0">
                <table class="table table-striped projects">
                    <tbody>
                    @foreach($listOrder as $order)
                        <tr class="search-for-index-{{ $order[\App\Constants\BaseConstant::ID_FIELD] }}">
                            <td width="40%" class="custom-td">
                                <span>
                                    {{ $tableList[$order[\App\Constants\OrderConstant::TABLE_ID_FIELD]] }}
                                </span>
                            </td>
                            <td class="text-right custom-td" width="40%">
                                <a href="{{ route('detail-order', ['orderId' => $order[\App\Constants\BaseConstant::ID_FIELD]]) }}" class="btn btn-info">Xem chi tiết</a>
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
