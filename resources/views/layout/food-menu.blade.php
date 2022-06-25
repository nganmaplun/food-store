<!-- Brand Logo -->
<a href="{{route('admin-dashboard')}}" class="brand-link">
    <img src="{{asset('image/logo.png')}}" alt="" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">Food Store</span>
</a>

<div class="sidebar">
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item admin-sidebar {{$route == 'timesheet' ? 'active' : ''}}">
                <a href="{{route('timesheet')}}" class="nav-link">
                    <i class="nav-icon fa fa-chevron-right"></i>
                    <p>Salad</p>
                </a>
            </li>
            <li class="nav-item admin-sidebar {{$route == 'admin.food' ? 'active' : ''}}">
                <a href="{{route('admin.food')}}" class="nav-link">
                    <i class="nav-icon fa fa-chevron-right"></i>
                    <p>Đồ nướng</p>
                </a>
            </li>
            <li class="nav-item admin-sidebar">
                <a href="#" class="nav-link">
                    <i class="nav-icon fa fa-chevron-right"></i>
                    <p>Đồ hầm</p>
                </a>
            </li>
            <li class="nav-item admin-sidebar">
                <a href="#" class="nav-link">
                    <i class="nav-icon fa fa-chevron-right"></i>
                    <p>Đồ chiên</p>
                </a>
            </li>
            <li class="nav-item admin-sidebar">
                <a href="#" class="nav-link">
                    <i class="nav-icon fa fa-chevron-right"></i>
                    <p>Đồ uống</p>
                </a>
            </li>
        </ul>
    </nav>
</div>
