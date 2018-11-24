<?php
$settings = \App\Helpers\General::get_settings();
?>
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1"><![endif]-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="robots" content="index, follow">

    <title>Đăng nhập hệ thống</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <link href="/html/assets/css/style.css?v=<?=time()?>" rel="stylesheet">
    <!-- Favicon -->
    <link rel="icon" href="<?=$settings['favicon_ico']['value'] ?? ''?>" type="image/x-icon" sizes="16x16" >

</head>
<body class="wrap-login" style="background: url('/html/assets/images/bg-login.png?v=1') no-repeat;background-size: 100% 100%;">
    <section>
        @yield('content')
    </section>

    <script src="/html/assets/js/lib/jquery.js" type="text/javascript"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="/html/assets/js/lib/jquery-ui.min.js" type="text/javascript"></script>
    <script src="/html/assets/js/lib/jquery.checkall.js" type="text/javascript"></script>

</body>

</html>
