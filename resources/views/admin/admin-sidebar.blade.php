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
                    <p>Duyệt checkin</p>
                </a>
            </li>
            <li class="nav-item admin-sidebar {{$route == 'admin.food' ? 'active' : ''}}">
                <a href="{{route('admin.food')}}" class="nav-link">
                    <i class="nav-icon fa fa-chevron-right"></i>
                    <p>Setting món</p>
                </a>
            </li>
            <li class="nav-item admin-sidebar">
                <a href="#" class="nav-link">
                    <i class="nav-icon fa fa-chevron-right"></i>
                    <p>Tình trạng bàn</p>
                </a>
            </li>
            <li class="nav-item admin-sidebar">
                <a href="#" class="nav-link">
                    <i class="nav-icon fa fa-chevron-right"></i>
                    <p>Xem thống kê</p>
                </a>
            </li>
            <li class="nav-item admin-sidebar">
                <a href="#" class="nav-link">
                    <i class="nav-icon fa fa-chevron-right"></i>
                    <p>Thêm nhân viên</p>
                </a>
            </li>
            <li class="nav-item admin-sidebar">
                <a href="#" class="nav-link">
                    <i class="nav-icon fa fa-chevron-right"></i>
                    <p>Danh sách nhân viên</p>
                </a>
            </li>
            <li class="nav-item admin-sidebar">
                <a href="#" class="nav-link">
                    <i class="nav-icon fa fa-chevron-right"></i>
                    <p>Thêm món ăn</p>
                </a>
            </li>
            <li class="nav-item admin-sidebar">
                <a href="#" class="nav-link">
                    <i class="nav-icon fa fa-chevron-right"></i>
                    <p>Danh sách món ăn</p>
                </a>
            </li>
            <li class="nav-item admin-sidebar">
                <a href="#" class="nav-link">
                    <i class="nav-icon fa fa-chevron-right"></i>
                    <p>Danh sách order hiện tại</p>
                </a>
            </li>
            <li class="nav-item admin-sidebar">
                <a href="#" class="nav-link">
                    <i class="nav-icon fa fa-chevron-right"></i>
                    <p>Thêm bàn</p>
                </a>
            </li>
            <li class="nav-item admin-sidebar">
                <a href="#" class="nav-link">
                    <i class="nav-icon fa fa-chevron-right"></i>
                    <p>Danh sách bàn</p>
                </a>
            </li>
        </ul>
    </nav>
</div>
