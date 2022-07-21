<?php
use Carbon\Carbon;
?>
@extends('layout.no-menubar')

@section('content')
<ul class="nav justify-content-center pt-3">
    <li class="nav-item d-none d-sm-block">
        <a class="nav-link btn btn-default {{ $category == 1 ? 'bg-dark-red' : '' }}"
            href="{{ route('chef-dashboard', ['category' => 1])}}">{{
            \App\Constants\BaseConstant::ARRAY_KITCHEN[\App\Constants\BaseConstant::FOOD_SALAD] }}</a>
    </li>
    <li class="nav-item d-none d-sm-block">
        <a class="nav-link btn btn-default {{ $category == 2 ? 'bg-dark-red' : '' }}"
            href="{{ route('chef-dashboard', ['category' => 2])}}">{{
            \App\Constants\BaseConstant::ARRAY_KITCHEN[\App\Constants\BaseConstant::FOOD_GRILL] }}</a>
    </li>
    <li class="nav-item d-none d-sm-block">
        <a class="nav-link btn btn-default {{ $category == 3 ? 'bg-dark-red' : '' }}"
            href="{{ route('chef-dashboard', ['category' => 3])}}">{{
            \App\Constants\BaseConstant::ARRAY_KITCHEN[\App\Constants\BaseConstant::FOOD_STEAM] }}</a>
    </li>
    <li class="nav-item d-none d-sm-block">
        <a class="nav-link btn btn-default {{ $category == 4 ? 'bg-dark-red' : '' }}"
            href="{{ route('chef-dashboard', ['category' => 4])}}">{{
            \App\Constants\BaseConstant::ARRAY_KITCHEN[\App\Constants\BaseConstant::FOOD_DRYING] }}</a>
    </li>
    <li class="nav-item d-block d-sm-none">
        <a class="nav-link btn btn-default {{ $category == 1 ? 'bg-dark-red' : '' }}"
            href="{{ route('chef-dashboard', ['category' => 1])}}">{{
            \App\Constants\BaseConstant::ARRAY_KITCHEN_SHORT[\App\Constants\BaseConstant::FOOD_SALAD] }}</a>
    </li>
    <li class="nav-item d-block d-sm-none">
        <a class="nav-link btn btn-default {{ $category == 2 ? 'bg-dark-red' : '' }}"
            href="{{ route('chef-dashboard', ['category' => 2])}}">{{
            \App\Constants\BaseConstant::ARRAY_KITCHEN_SHORT[\App\Constants\BaseConstant::FOOD_GRILL] }}</a>
    </li>
    <li class="nav-item d-block d-sm-none">
        <a class="nav-link btn btn-default {{ $category == 3 ? 'bg-dark-red' : '' }}"
            href="{{ route('chef-dashboard', ['category' => 3])}}">{{
            \App\Constants\BaseConstant::ARRAY_KITCHEN_SHORT[\App\Constants\BaseConstant::FOOD_STEAM] }}</a>
    </li>
    <li class="nav-item d-block d-sm-none">
        <a class="nav-link btn btn-default {{ $category == 4 ? 'bg-dark-red' : '' }}"
            href="{{ route('chef-dashboard', ['category' => 4])}}">{{
            \App\Constants\BaseConstant::ARRAY_KITCHEN_SHORT[\App\Constants\BaseConstant::FOOD_DRYING] }}</a>
    </li>
</ul>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Danh sách món hiện tại</h1>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<section class="content pt-3">
    <div class="card">
        <div class="card-body p-0">
            <table class="table table-sm projects">
                <thead>
                    <tr>
                        <th style="width: 10%">Bàn</th>
                        <th style="width: 40%">Món</th>
                        <th style="width: 20%">Số lượng</th>
                        <th style="width: 20%">Giờ order</th>
                        <th style="width: 30%"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($listFoods as $key => $food)
                    <tr class="search-for-index-{{ $food[\App\Constants\BaseConstant::ID_FIELD] }} {{ (strtotime(Carbon::now()->toTimeString()) - strtotime($food[\App\Constants\FoodOrderConstant::ORDER_TIME_FIELD]))/60 <= 15 ? 'bg-light-blue' : '' }}">
                        <td width="20%" class="custom-td">
                            <div class="col-12">
                                {{ $food[\App\Constants\TableConstant::NAME_FIELD] }}
                            </div>
                        </td>
                        <td width="40%" class="custom-td">
                            <a onclick="openModal(this)" href="javascript:void(0)" key="{{ $key }}">
                                {{ $food[\App\Constants\FoodConstant::VIETNAMESE_NAME_FIELD] }}
                            </a>
                            @if ($food[\App\Constants\FoodOrderConstant::NOTE_FIELD])
                            <br />
                            <small class="text-info">
                                {{ $food[\App\Constants\FoodOrderConstant::NOTE_FIELD] }}
                            </small>
                            @endif
                        </td>
                        <td width="20%" class="custom-td">
                            <div class="col-12">
                                {{ $food[\App\Constants\FoodOrderConstant::ORDER_NUM_FIELD] }}
                            </div>
                        </td>
                        <td class="custom-td">{{ $food[\App\Constants\FoodOrderConstant::ORDER_TIME_FIELD] }}</td>
                        <td class="text-right custom-td" width="40%">
                            <button class="btn btn-outline-primary check-food"
                                index="{{ $food[\App\Constants\FoodOrderConstant::ORDER_ID_FIELD] }}"
                                rel="{{ $food['tblId'] }}" food="{{ $food[\App\Constants\BaseConstant::ID_FIELD] }}">Trả
                                món</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
    </div>
    @include('chef.modal-food-recipe')
</section>
@endsection

@section('script')
<script>
    var listFoods = "{{json_encode($listFoods->toArray())}}"
    var urlSendFoodToWaiter = "{{ route('send-message') }}";
</script>
<script>
    let link = "{{ route('chef-dashboard', ['category' => $category]) }}"
        let bodyText = "<span>Hãy bấm vào <a style='color: red' href='" + link + "'>đây</a> để kiểm tra lại danh sách món</span>";

        var pusher = new Pusher(app_key, {
            cluster: 'ap1',
            encrypted: true
        });

        var channel = pusher.subscribe('{{ $event }}');

        channel.bind('{{ $channel }}', function(data) {
            $(document).Toasts('create', {
                class: 'bg-info',
                title: 'Có thay đổi order',
                body: bodyText
            })
        });
</script>
<script src="{{ asset('js/chef-dashboard.js' )}}"></script>
@endsection
