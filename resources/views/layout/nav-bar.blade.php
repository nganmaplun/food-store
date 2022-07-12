<?php
use App\Constants\UserConstant;
?>
@if ($route === 'food-list')
<ul class="navbar-nav">
    <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
</ul>
@else
<a href="{{route($dashboard)}}" class="brand-link">
    <img src="{{asset('image/logo.png')}}" alt="" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">Food Store</span>
</a>
@endif
<ul class="navbar-nav ml-auto">
    <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="javascript:void(0)"> Xin chào, {{$user[UserConstant::FULLNAME_FIELD] ?? ''}}
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

