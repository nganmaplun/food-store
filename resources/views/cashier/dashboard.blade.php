@extends('layout.no-menubar')
<?php
    use Illuminate\Support\Facades\URL;
?>
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Danh sách bàn ăn</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <ul class="nav justify-content-center pt-3">
        <li class="nav-item">
            <a class="nav-link btn btn-default" href="{{URL::route('cashier-dashboard', ['floor' => 'all'])}}">All</a>
        </li>
        @foreach($listFloors as $floor)
        <li class="nav-item">
            <a class="nav-link btn btn-default" href="{{URL::route('cashier-dashboard', ['floor' => $floor[\App\Constants\TableConstant::FLOOR_FIELD]])}}">Tầng {{ $floor[\App\Constants\TableConstant::FLOOR_FIELD] }}</a>
        </li>
        @endforeach
        </li>
    </ul>
    @if (\Session::has('status'))
        <div class="alert alert-success">
            <span>{!! \Session::get('status') !!}</span>
        </div>
    @endif
    <section class="content pt-3">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                            @foreach($listTables as $key => $table)
                                <div class="col-4 pt-4" index="{{ $table[\App\Constants\BaseConstant::ID_FIELD] }}" rel="{{ $lstCount['table_order'][$table[\App\Constants\BaseConstant::ID_FIELD]] ?? '' }}">
                                    <div class="position-relative p-3 {{ $table[\App\Constants\BaseConstant::STATUS_FIELD] == 0 ? 'bg-green' : ($table[\App\Constants\BaseConstant::STATUS_FIELD] == 1 ?  'bg-red' : 'bg-yellow') }}" style="height: 180px">
                                        <div class="ribbon-wrapper ribbon">
                                            <div class="ribbon bg-gradient-light ribbon-text">
                                                {{ $table[\App\Constants\BaseConstant::STATUS_FIELD] == 0 ? 'Bàn trống' : ($table[\App\Constants\BaseConstant::STATUS_FIELD] == 1 ?  'Bàn order' : 'Bàn thanh toán') }}
                                            </div>
                                        </div>
                                        {{ $table[\App\Constants\TableConstant::NAME_FIELD] }}<br />
                                        @if ($table[\App\Constants\BaseConstant::STATUS_FIELD] == 1)
                                        <small>Tổng số món order : {{ $lstCount['total_order'][$table[\App\Constants\BaseConstant::ID_FIELD]] }}</small><br />
                                        <small>Tổng số món đã xong : {{ $lstCount['total_completed'][$table[\App\Constants\BaseConstant::ID_FIELD]] }}</small><br />
                                        <small class="total_inorder" rel="{{ $lstCount['total_inorder'][$table[\App\Constants\BaseConstant::ID_FIELD]] }}">Tổng số món đang order : {{ $lstCount['total_inorder'][$table[\App\Constants\BaseConstant::ID_FIELD]] }}</small>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <div class="card-footer">
            {{ $listTables->links('vendor.pagination.bootstrap-4', ['page' => $page]) }}
        </div>
    </section>
@endsection
@section('script')
    <script>
        var changeStatusUrl = "{{ route('change-table-status') }}";
        var createOrder = "{{ route('create-order') }}";
        setTimeout(function () {
            $('.alert-success').css('display', 'none');
        }, 2000);
        $(function() {
            $(document).on('click', '.bg-red', function() {
                let index = $(this).parent().attr('index');
                let msgRed = 'Thanh toán cho bàn này?';
                if ($(this).hasClass('bg-red')) {
                    if (confirm(msgRed)) {
                        let inOrder = $(this).find('.total_inorder').attr('rel');
                        if (parseInt(inOrder) > 0) {
                            if (confirm('Bàn chưa đưa hết đồ ăn lên, có chắc muốn thanh toán?')) {
                                let rel = $(this).parent().attr('rel');
                                let url = "{{ route('cashier.detail-order', [":index"]) }}";
                                url = url.replace(':index', rel);
                                location.href = url;
                                return
                            }
                        }
                        let rel = $(this).parent().attr('rel');
                        let url = "{{ route('cashier.detail-order', [":index"]) }}";
                        url = url.replace(':index', rel);
                        location.href = url;
                        return
                    }
                }
            })
        })
    </script>
    <script src="{{ asset('js/waiter-dashboard.js' )}}"></script>
    <script src="{{ asset('js/custom-waiter-only.js' )}}"></script>
@endsection
