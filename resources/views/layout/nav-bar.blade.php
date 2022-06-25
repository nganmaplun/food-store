<?php
use App\Constants\UserConstant;
?>
<ul class="navbar-nav ml-auto">
    <!-- Notifications Dropdown Menu -->
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

