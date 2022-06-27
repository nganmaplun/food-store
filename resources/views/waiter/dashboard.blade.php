@extends('layout.layout')
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
            <a class="nav-link btn btn-default" href="{{URL::route('waiter-dashboard', ['floor' => 'all'])}}">All</a>
        </li>
        @foreach($listFloors as $floor)
        <li class="nav-item">
            <a class="nav-link btn btn-default" href="{{URL::route('waiter-dashboard', ['floor' => $floor[\App\Constants\TableConstant::FLOOR_FIELD]])}}">Tầng {{ $floor[\App\Constants\TableConstant::FLOOR_FIELD] }}</a>
        </li>
        @endforeach
        </li>
    </ul>

    <section class="content pt-3">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                            @foreach($listTables as $key => $table)
                                <div class="col-sm-4 pt-4" index="{{ $table[\App\Constants\BaseConstant::ID_FIELD] }}">
                                    <div class="position-relative p-3 {{ $table[\App\Constants\BaseConstant::STATUS_FIELD] == 0 ? 'bg-green' : ($table[\App\Constants\BaseConstant::STATUS_FIELD] == 1 ?  'bg-red' : 'bg-yellow') }}" style="height: 180px">
                                        <div class="ribbon-wrapper ribbon-lg">
                                            <div class="ribbon bg-gradient-light">
                                                {{ $table[\App\Constants\BaseConstant::STATUS_FIELD] == 0 ? 'Bàn trống' : ($table[\App\Constants\BaseConstant::STATUS_FIELD] == 1 ?  'Bàn order' : 'Bàn thanh toán') }}
                                            </div>
                                        </div>
                                        {{ $table[\App\Constants\TableConstant::NAME_FIELD] }}<br />
                                        @if ($table[\App\Constants\BaseConstant::STATUS_FIELD] == 1)
                                        <small>.ribbon-wrapper.ribbon-lg .ribbon</small>
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
        @include('waiter.modal-create-order')
    </section>
@endsection
@section('script')
    <script>
        var changeStatusUrl = "{{ route('change-table-status') }}";
        var createOrder = "{{ route('create-order') }}";
        $(function() {
            $(document).on('click', '.bg-green, .bg-red, .bg-yellow', function() {
                let index = $(this).parent().attr('index');
                let msgYellow = 'Bạn có muốn chuyển bàn này thành bàn trống?';
                let msgGreen = 'Order cho bàn này?'
                if ($(this).hasClass('bg-green')) {
                    if (confirm(msgGreen)) {
                        $('#modal-order').modal('show');
                        $(document).on('click', '.add-order', async function() {
                            let guestType = $('#modal-order').find('.guest-type').val();
                            let guestNum = $('#modal-order').find('.guest-num').val();
                            let otherNote = $('#modal-order').find('.other-note').val();
                            let orderId = await sendRequestCreateOrder(index, guestType, guestNum, otherNote).then(function(s) {
                                if (s.code == '333') {
                                    alert(r.message)
                                    return location.reload();
                                }
                                return s.order_id;
                            })
                            sendRequestChangeStatus(index).then(function(r) {
                                if (r.code != '333') {
                                    let url = "{{ route('food-list', [":index", ":orderId"]) }}";
                                    url = url.replace(':index', index);
                                    url = url.replace(':orderId', orderId);
                                    location.href = url;
                                    return
                                }
                                alert(r.message)
                                location.reload();
                            })
                        })

                    }
                }
                if ($(this).hasClass('bg-yellow')) {
                    if (confirm(msgYellow)) {
                        sendRequestChangeStatus(index).then(function(r) {
                            alert(r.message)
                        })
                    }
                }
            })
        })
    </script>
    <script src="{{ asset('js/waiter-dashboard.js' )}}"></script>
@endsection
