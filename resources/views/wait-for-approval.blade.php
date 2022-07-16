<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Food Store</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('css/font-awesome.min.css')}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('css/adminlte.min.css')}}">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{asset('css/custom.css')}}">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <div class="content-wrapper custom-content">
            @if ($isApproved === false)
            <div class="alert alert-info text-center">
                <h2 class="display-3">Xin Chào</h2>
                <p class="display-5">Hãy chờ quản lý duyệt để bắt đầu làm việc</p>
                <p class="display-5">Nếu thời gian chờ quá lâu, vui lòng nhắc nhở quản lý</p>
            </div>
            @endif
        </div>
    </div>
</body>
<footer class="main-footer" style="text-align: center;margin-left: 0">
    <strong>Copyright &copy; 2022 Food Store</strong>
</footer>
<!-- jQuery -->
<script src="{{asset('js/jquery.min.js')}}"></script>
<script>
    $(function () {
        setInterval(function () {
            window.location.reload()
        }, 5000);
    })
</script>
