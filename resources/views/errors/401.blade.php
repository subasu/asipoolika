<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Site Meta Info -->
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="املاک آنلاین خمینی شهر">
    <meta name="keywords" content="املاک آنلاین خمینی شهر،خمینی شهر،املاک آنلاین،خرید و فروش آنلاین ملک،لیست املاک خمینی شهر،املاک خمینی شهر،مشاور املاک در خمینی شهر،مشاور املاک منظریه،مشاور املاک پاسدارن،مشاور املاک هفتصد دستگاه">
    <meta name="author" content="www.melkomalek.ir">
    <title>اجازه دسترسی ندارید!</title>

    <!-- Bootstrap core CSS -->

    <link href="{{ URL::asset('public/dashboard/css/bootstrap.min.css')}}" rel="stylesheet">

    <link href="{{ URL::asset('public/dashboard/fonts/css/font-awesome.min.css')}}" rel="stylesheet">
    <link href="{{ URL::asset('public/dashboard/css/animate.min.css')}}" rel="stylesheet">
    {{--My Style Code--}}
    <link href="{{ URL::asset('public/dashboard/css/mystyle.css')}}" rel="stylesheet">

    <!-- Custom styling plus plugins -->
    <link href="{{ URL::asset('public/dashboard/css/custom.css')}}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('public/dashboard/css/maps/jquery-jvectormap-2.0.3.css')}}" />
    <link href="{{ URL::asset('public/dashboard/css/icheck/flat/green.css')}}" rel="stylesheet" />
    <link href="{{ URL::asset('public/dashboard/css/floatexamples.css')}}" rel="stylesheet" type="text/css" />

    <script src="{{ URL::asset('public/js/jquery.min.js')}}"></script>

    <!--[if lt IE 9]>
    <script src="../assets/js/ie8-responsive-file-warning.js"></script>
    <![endif]-->

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style> div {font-family:"B Yekan";}</style>
</head>


<body class="nav-md">

<div class="container body">
    <div class="main_container">
        <!-- page content -->
        <div class="col-md-12" style="color: white">
            <div class="col-middle">
                <div class="text-center text-center">
                    <div class="row">
                        <img class="img-responsive col-md-2 col-md-offset-5" src="{{ URL::asset('public/dashboard/images/forbidden.png')}}" alt="Chania">
                    </div>

                    <h1 class="error-number">401</h1>
                    <h1>شما اجازه دسترسی به این صفحه را ندارید</h1>
                    <br>
                    <h2><a href="{{url('/login')}}" style="color: white">بازگشت</a>
                    </h2>

                </div>
            </div>
        </div>
        <!-- /page content -->

    </div>
    <!-- footer content -->
</div>

<div id="custom_notifications" class="custom-notifications dsp_none">
    <ul class="list-unstyled notifications clearfix" data-tabbed_notifications="notif-group">
    </ul>
    <div class="clearfix"></div>
    <div id="notif-group" class="tabbed_notifications"></div>
</div>
</body>

</html>
