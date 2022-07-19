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
            <div class="col-sm-6 row justify-content-end">
                <button class="btn btn-info btn-combine-table">Gộp bàn</button>
                <div class="pl-2"></div>
                <a href="{{ route('food-stand') }}" class="btn btn-info pl-2">Quầy chờ</a>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<ul class="nav justify-content-center pt-3">
    <li class="nav-item">
        <a class="nav-link btn btn-default d-none d-sm-block {{ $currentFloor == 'all' || !$currentFloor ? 'bg-dark-orange' : ''}}"
            href="{{URL::route('waiter-dashboard', ['floor' => 'all'])}}">All</a>
        <a class="nav-link btn btn-default d-block d-sm-none custom-small {{ $currentFloor == 'all' || !$currentFloor ? 'bg-dark-orange' : ''}}"
            href="{{URL::route('waiter-dashboard', ['floor' => 'all'])}}">All</a>
    </li>
    @foreach($listFloors as $floor)
    <li class="nav-item">
        <a class="nav-link btn btn-default d-none d-sm-block {{ $floor[\App\Constants\TableConstant::FLOOR_FIELD] == $currentFloor ? 'bg-dark-orange' : ''}}"
            href="{{URL::route('waiter-dashboard', ['floor' => $floor[\App\Constants\TableConstant::FLOOR_FIELD]])}}">Tầng
            {{ $floor[\App\Constants\TableConstant::FLOOR_FIELD] }}</a>
        <a class="nav-link btn btn-default d-block d-sm-none custom-small {{ $floor[\App\Constants\TableConstant::FLOOR_FIELD] == $currentFloor ? 'bg-dark-orange' : ''}}"
            href="{{URL::route('waiter-dashboard', ['floor' => $floor[\App\Constants\TableConstant::FLOOR_FIELD]])}}">Tầng
            {{ $floor[\App\Constants\TableConstant::FLOOR_FIELD] }}</a>
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
                            <div class="col-4 pt-4" index="{{ $table[\App\Constants\BaseConstant::ID_FIELD] }}"
                                rel="{{ $lstCount['table_order'][$table[\App\Constants\BaseConstant::ID_FIELD]] ?? '' }}">
                                <div class="position-relative p-3 {{ $table[\App\Constants\BaseConstant::STATUS_FIELD] == 0 ? 'bg-green' : ($table[\App\Constants\BaseConstant::STATUS_FIELD] == 1 ?  'bg-red' : 'bg-yellow') }}"
                                    style="height: 180px">
                                    <div class="ribbon-wrapper ribbon">
                                        <div class="ribbon bg-gradient-light ribbon-text">
                                            {{ $table[\App\Constants\BaseConstant::STATUS_FIELD] == 0 ? 'Bàn trống' :
                                            ($table[\App\Constants\BaseConstant::STATUS_FIELD] == 1 ? 'Bàn order' : 'Bàn
                                            thanh toán') }}
                                        </div>
                                    </div>
                                    <h5 class="d-none d-sm-block">{{
                                        $table[\App\Constants\TableConstant::NAME_FIELD] }}</h5>
                                    <h5 class="d-block d-sm-none pt-3">{{
                                        $table[\App\Constants\TableConstant::NAME_FIELD] }}
                                    </h5>
                                    <br />
                                    @if ($table[\App\Constants\BaseConstant::STATUS_FIELD] == 1)
                                    <small class="d-none d-sm-block">Tổng số món order : {{
                                        $lstCount['total_order'][$table[\App\Constants\BaseConstant::ID_FIELD]]
                                        }}</small>
                                    <small class="d-none d-sm-block">Tổng số món đã xong : {{
                                        $lstCount['total_completed'][$table[\App\Constants\BaseConstant::ID_FIELD]]
                                        }}</small>
                                    <small class="d-none d-sm-block">Tổng số món đang order : {{
                                        $lstCount['total_inorder'][$table[\App\Constants\BaseConstant::ID_FIELD]]
                                        }}</small>
                                    <small class="d-block d-sm-none custom-small">Tổng : {{
                                        $lstCount['total_order'][$table[\App\Constants\BaseConstant::ID_FIELD]]
                                        }}</small>
                                    <small class="d-block d-sm-none custom-small">Đã xong : {{
                                        $lstCount['total_completed'][$table[\App\Constants\BaseConstant::ID_FIELD]]
                                        }}</small>
                                    <small class="d-block d-sm-none custom-small">Đang order : {{
                                        $lstCount['total_inorder'][$table[\App\Constants\BaseConstant::ID_FIELD]]
                                        }}</small>
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
    @include('waiter.modal-combine-table')
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
                            if (guestNum == '' || parseInt(guestNum) == 0) {
                                alert('Hãy điền số lượng khách');
                                return false;
                            }
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
                                    let url = "{{ route('view.order', [":index", ":orderId"]) }}";
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
                        let code = sendRequestChangeStatus(index).then(function(r) {
                            alert(r.message)
                            return r.code;
                        })
                        code.then(() => {
                            $(this).removeClass('bg-yellow').addClass('bg-green');
                            $(this).find('.ribbon-text').text('Bàn trống');
                        });
                    }
                }
                if ($(this).hasClass('bg-red')) {
                    let rel = $(this).parent().attr('rel');
                    let url = "{{ route('view.order', [":index", ":orderId"]) }}";
                    url = url.replace(':index', index);
                    url = url.replace(':orderId', rel);
                    location.href = url;
                    return
                }
            })

            $(document).on('click', '.btn-combine-table', function() {
                $('#modal-combine-table').modal('show');
                $(document).on('click', '.combine-table', function () {
                    let listTables = $('#multi-table').val()
                    if (listTables.length <= 1) {
                        alert('Hãy chọn bàn để gộp');
                        return false;
                    }
                    let index = listTables[0];
                    $('#modal-order').modal('show');
                    $('#modal-combine-table').modal('hide');
                    $(document).on('click', '.add-order', async function() {
                        let guestType = $('#modal-order').find('.guest-type').val();
                        let guestNum = $('#modal-order').find('.guest-num').val();
                        if (guestNum == '' || parseInt(guestNum) == 0) {
                            alert('Hãy điền số lượng khách');
                            return false;
                        }
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
                                for (let i=1; i < listTables.length; i++) {
                                    sendRequestChangeStatus(listTables[i]);
                                    createSubOrderTable(listTables[i], orderId, guestType, guestNum, otherNote);
                                }
                                location.href = url;
                                return
                            }
                            alert(r.message)
                            location.reload();
                        })
                    })
                })
            })
        })
</script>
<script src="{{ asset('js/waiter-dashboard.js' )}}"></script>
<script src="{{ asset('js/custom-waiter-only.js' )}}"></script>
@endsection
