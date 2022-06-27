<?php
use App\Constants\UserConstant;
?>
@if ($route !== 'waiter-dashboard')
<ul class="navbar-nav">
    <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
</ul>
@endif
<ul class="navbar-nav ml-auto">
    <li class="nav-item">
        <a class="nav-link" data-toggle="dropdown" href="javascript:void(0)"> Xin chào, {{$user[UserConstant::FULLNAME_FIELD] ?? ''}}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{route('post.logout')}}" role="button">
            <i class="fas fa-sign-out-alt"></i>
        </a>
    </li>
</ul>

