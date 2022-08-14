<!-- Brand Logo -->
<a href="{{route('admin-dashboard')}}" class="brand-link">
    <img src="{{asset('storage/logo.png')}}" alt="" class="brand-image img-circle elevation-3" style="opacity: .8">
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
            <li class="nav-item admin-sidebar {{$route == 'admin.aggOrder' ? 'active' : ''}}">
                <a href="{{ route('admin.aggOrder') }}" class="nav-link">
                    <i class="nav-icon fa fa-chevron-right"></i>
                    <p>Xem thống kê</p>
                </a>
            </li>
            <li class="nav-item admin-sidebar {{$route == 'view-create-employee' ? 'active' : ''}}">
                <a href="{{ route('view-create-employee') }}" class="nav-link">
                    <i class="nav-icon fa fa-chevron-right"></i>
                    <p>Thêm nhân viên</p>
                </a>
            </li>
            <li class="nav-item admin-sidebar {{$route == 'list-employee' ? 'active' : ''}}">
                <a href="{{ route('list-employee') }}" class="nav-link">
                    <i class="nav-icon fa fa-chevron-right"></i>
                    <p>Danh sách nhân viên</p>
                </a>
            </li>
            <li class="nav-item admin-sidebar {{$route == 'view-create-food' ? 'active' : ''}}">
                <a href="{{ route('view-create-food') }}" class="nav-link">
                    <i class="nav-icon fa fa-chevron-right"></i>
                    <p>Thêm món ăn</p>
                </a>
            </li>
            <li class="nav-item admin-sidebar {{$route == 'list-food' ? 'active' : ''}}">
                <a href="{{ route('list-food') }}" class="nav-link">
                    <i class="nav-icon fa fa-chevron-right"></i>
                    <p>Danh sách món ăn</p>
                </a>
            </li>
            <li class="nav-item admin-sidebar" id="reset-table">
                <a href="javascript:void(0)" class="nav-link">
                    <i class="nav-icon fa fa-chevron-right"></i>
                    <p>Reset bàn</p>
                </a>
            </li>
            <li class="nav-item admin-sidebar {{$route == 'admin-dashboard' ? 'active' : ''}}">
                <a href="{{ route('admin-dashboard') }}" class="nav-link">
                    <i class="nav-icon fa fa-chevron-right"></i>
                    <p>Duyệt chạy bàn</p>
                </a>
            </li>
            <li class="nav-item admin-sidebar {{$route == 'admin-chef' ? 'active' : ''}}">
                <a href="{{ route('admin-chef', ['category' => \App\Constants\BaseConstant::FOOD_SALAD]) }}" class="nav-link">
                    <i class="nav-icon fa fa-chevron-right"></i>
                    <p>Xem món ở bếp</p>
                </a>
            </li>
            <li class="nav-item admin-sidebar {{$route == 'admin-cashier' ? 'active' : ''}}">
                <a href="{{ route('admin-cashier') }}" class="nav-link">
                    <i class="nav-icon fa fa-chevron-right"></i>
                    <p>Duyệt thanh toán</p>
                </a>
            </li>
        </ul>
    </nav>
</div>
