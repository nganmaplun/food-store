<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Food Store</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('css/font-awesome.min.css')}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="{{asset('css/tempusdominus-bootstrap-4.min.css')}}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{asset('css/icheck-bootstrap.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('css/adminlte.min.css')}}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{asset('css/OverlayScrollbars.min.css')}}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{asset('css/daterangepicker.css')}}">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{asset('css/custom.css')}}">
    <!-- other CSS -->
    @yield('other-css')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <nav class="main-header navbar navbar-expand navbar-white navbar-light bg-red-o {{ $route !== 'waiter-dashboard' ? '' : 'custom-header' }}  {{ $role == 'admin' ? 'bg-nav-admin' : ($role == 'waiter/waitress' ? 'bg-nav-waiter custom-font-color' : ($role == 'cashier' ? 'bg-nav-cashier' : 'bg-nav-chef')) }}">
        @include('layout.nav-bar')
    </nav>
    @if (in_array($route, ['food-list', 'food-list-by-menu']))
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        @include('layout.food-menu')
    </aside>
    @endif
    <div class="content-wrapper">
        @yield('content')
    </div>
</div>
</body>
<footer class="main-footer" style="text-align: center;margin-left: 0">
    <strong>Copyright &copy; 2022 Food Store</strong>
</footer>
<script>
    var app_key = "{{ env('PUSHER_APP_KEY') }}";
    var domain = "{{ $domain }}";
</script>
<!-- jQuery -->
<script src="{{asset('js/jquery.min.js')}}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{asset('js/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{asset('js/bootstrap.bundle.min.js')}}"></script>
<!-- daterangepicker -->
<script src="{{asset('js/moment.min.js')}}"></script>
<script src="{{asset('js/daterangepicker.js')}}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{asset('js/tempusdominus-bootstrap-4.min.js')}}"></script>
<!-- overlayScrollbars -->
<script src="{{asset('js/jquery.overlayScrollbars.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('js/adminlte.js')}}"></script>
<!-- Pusher -->
<script src="{{ asset('js/pusher.min.js' )}}"></script>
<!-- Custom script -->
<script src="{{asset('js/custom.js')}}"></script>
@yield('script')
