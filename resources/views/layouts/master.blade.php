<!DOCTYPE html>
<html lang="en">
<?php
$version_js = \App\Helpers\General::get_version_js();
$version_css = \App\Helpers\General::get_version_css();
$settings = \App\Helpers\General::get_settings();
?>
<head>
    <meta charset="utf-8">
    <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1"><![endif]-->
    <!--<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="robots" content="index, follow">

    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>
      {{ isset($title) ? $title.' :: '.config('backpack.base.project_name') : config('backpack.base.project_name') }}
    </title>

    <!-- Styles -->
    <!-- <link href="/html/assets/css/bootstrap.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <link href="/html/assets/css/bootstrap-select.css" rel="stylesheet">
    <link href="/html/assets/css/bootstrap-datepicker.css" rel="stylesheet">
    <link href="/html/assets/css/swiper.min.css" rel="stylesheet">
    <link href="/html/assets/css/component.css" rel="stylesheet">
    <link href="/html/assets/css/demo.css" rel="stylesheet">
    <link href="/html/assets/css/style.css?v={{$version_css}}" rel="stylesheet">
    <link href="/css/customs.css?v={{$version_css}}" rel="stylesheet">
    <link href="/css/bootstrap-multiselect.css?v={{$version_css}}" rel="stylesheet">

    <link href="{{ asset('vendor/backpack/select2/select2.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('vendor/backpack/select2/select2-bootstrap-dick.css') }}" rel="stylesheet" type="text/css" />
    <!-- Favicon -->
    <link rel="icon" href="<?=$settings['favicon_ico']['value'] ?? ''?>" type="image/x-icon" sizes="16x16" >

    @yield('after_styles')

    <script type="text/javascript">
        var _outside_url = '<?=env('URL_OUTSIDE', '')?>';
        var _base_url = '{{url('/')}}';
        var _debug = <?=isset($_GET['debug']) ? intval($_GET['debug']) : 0 ?>;
    </script>
</head>

<body>
    @include('includes.header')
    <main>
        @include('includes.sidebar')
        @yield('content')
    </main>
    @include('includes.footer')

    <!--script-->
    <script src="/html/assets/js/lib/jquery.js" type="text/javascript"></script>
    <script src="/html/assets/js/lib/libs.js" type="text/javascript"></script>
    <script src="/html/assets/js/lib/bootstrap.min.js" type="text/javascript"></script>
    <script src="/html/assets/js/lib/bootstrap-select.js" type="text/javascript"></script>
    <script src="/js/bootstrap-multiselect.js" type="text/javascript"></script>
    <script src="/html/assets/js/lib/bootstrap-datepicker.js" type="text/javascript"></script>
    <script src="{{ asset('vendor/backpack/select2/select2.js') }}"></script>
    <script src="/html/assets/js/lib/amcharts.js"></script>
    <script src="/html/assets/js/lib/pie.js"></script>
    <script src="/html/assets/js/lib/jquery.checkall.js" type="text/javascript"></script>
    <script src="/html/assets/js/lib/chart.js" type="text/javascript"></script>
    <script src="/html/assets/js/plugin/swiper.min.js" type="text/javascript"></script>
    <script src="/js/jquery.validate.min.js" type="text/javascript"></script>
    <script src="/js/moment.min.js" type="text/javascript"></script>
    <script src="/js/script.js?v={{$version_js}}" type="text/javascript"></script>
    <script src="/js/numeral.min.js" type="text/javascript"></script>
    <script src="/js/functions.js?v={{$version_js}}" type="text/javascript"></script>
    <!-- page script -->
    <script type="text/javascript">
        // Ajax calls should always have the CSRF token attached to them, otherwise they won't work
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    @include('backpack::inc.alerts')

    @yield('after_scripts')
</body>

</html>