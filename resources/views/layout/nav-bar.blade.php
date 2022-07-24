<?php
use App\Constants\BaseConstant;
use App\Constants\UserConstant;
?>
@if ($route === 'food-list' || $role === BaseConstant::ADMIN_ROLE)
<ul class="navbar-nav">
    <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
</ul>
@else
@if(in_array($role, [BaseConstant::CHEF_DRYING_ROLE, BaseConstant::CHEF_GRILL_ROLE, BaseConstant::CHEF_SALAD_ROLE,
BaseConstant::CHEF_STEAM_ROLE, BaseConstant::CHEF_DRINK_ROLE]))
<a href="{{route($dashboard, ['category' => $categoryId])}}" class="brand-link">
    <img src="{{asset('storage/logo.png')}}" alt="" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light d-none d-sm-block">Food Store</span>
</a>
@elseif ($role !== BaseConstant::ADMIN_ROLE)
<a href="{{route($dashboard)}}" class="brand-link">
    <img src="{{asset('storage/logo.png')}}" alt="" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light d-none d-sm-block">Food Store</span>
</a>
@endif
@endif
<ul class="navbar-nav ml-auto">
    <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="javascript:void(0)">{{$user ?? ''}}
        </a>
        <div class="dropdown-menu dropdown-menu-right">
            <a href="{{ route('view.change-password') }}" class="dropdown-item">
                <div class="media">
                    <div class="media-body">
                        <h3 class="dropdown-item-title">Đổi mật khẩu
                        </h3>
                    </div>
                </div>
                <!-- Message End -->
            </a>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{route('post.logout')}}" role="button">
            <i class="fas fa-sign-out-alt"></i>
        </a>
    </li>
</ul>
