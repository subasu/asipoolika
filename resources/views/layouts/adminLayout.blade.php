<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<!-- Meta, title, CSS, favicons, etc. -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{$pageTitle}}</title>

<!-- Bootstrap core CSS -->

<link href="{{ URL::asset('public/dashboard/css/bootstrap.min.css')}}" rel="stylesheet">
<link href="{{ URL::asset('public/dashboard/fonts/css/font-awesome.min.css')}}" rel="stylesheet">
<link href="{{ URL::asset('public/dashboard/css/animate.min.css')}}" rel="stylesheet">
{{--dropzon--}}
<link href="{{ URL::asset('public/dashboard/css/dropzone.css')}}" rel="stylesheet">
<link href="{{ URL::asset('public/dashboard/css/dropzone.min.css')}}" rel="stylesheet">
{{--My Style Code--}}
<link href="{{ URL::asset('public/dashboard/css/mystyle.css')}}" rel="stylesheet">

<!-- Custom styling plus plugins -->
<link href="{{ URL::asset('public/dashboard/css/custom.css')}}" rel="stylesheet">
<link rel="stylesheet" type="text/css"
      href="{{ URL::asset('public/dashboard/css/maps/jquery-jvectormap-2.0.3.css')}}"/>
<link href="{{ URL::asset('public/dashboard/css/icheck/flat/green.css')}}" rel="stylesheet"/>
<link href="{{ URL::asset('public/dashboard/css/floatexamples.css')}}" rel="stylesheet" type="text/css"/>

<!-- editor -->
<link href="http://netdna.bootstrapcdn.com/font-awesome/3.0.2/css/font-awesome.css" rel="stylesheet">
<link href="{{URL::asset('public/dashboard/css/editor/external/google-code-prettify/prettify.css')}}"
      rel="stylesheet">
<link href="{{URL::asset('public/dashboard/css/editor/index.css')}}" rel="stylesheet">

<!--Data table-->
<link href="{{ URL::asset('public/dashboard/js/datatables/jquery.dataTables.min.css')}}" rel="stylesheet"
      type="text/css"/>
<link href="{{ URL::asset('public/dashboard/js/datatables/buttons.bootstrap.min.css')}}" rel="stylesheet"
      type="text/css"/>
<link href="{{ URL::asset('public/dashboard/js/datatables/fixedHeader.bootstrap.min.css')}}" rel="stylesheet"
      type="text/css"/>
<link href="{{ URL::asset('public/dashboard/js/datatables/responsive.bootstrap.min.css')}}" rel="stylesheet"
      type="text/css"/>
<link href="{{ URL::asset('public/dashboard/js/datatables/scroller.bootstrap.min.css')}}" rel="stylesheet"
      type="text/css"/>
<style>
    div,h2 {font-family:Yekan}
</style>

<!--End Data table-->

<link rel="stylesheet" type="text/css" href="{{URL::asset('public/css/sweetalert.css')}}">

<!-- Include a polyfill for ES6 Promises (optional) for IE11 and Android browser -->

<script src="{{ URL::asset('public/dashboard/js/jquery.min.js')}}"></script>
<script src="{{ URL::asset('public/dashboard/js/nprogress.js')}}"></script>

<!--[if lt IE 9]>
<script src="../assets/js/ie8-responsive-file-warning.js"></script>
<![endif]-->

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
</head>
<?php $user_info=\Illuminate\Support\Facades\Auth::user(); ?>
<input type="hidden" value="{{$user=\Illuminate\Support\Facades\Auth::user()}}">
<body class="nav-md">
<div class="container body">
    <div class="main_container">
        <div class="col-md-3 left_col">
            <div class="left_col scroll-view">
                <div class="clearfix"></div>

                <!-- menu profile quick info -->
                <div class="profile">
                    <div class="profile_pic">
                        <img src="{{URL::asset('public/dashboard/images/img.png')}}" alt="..."
                             class="img-circle profile_img">
                    </div>
                    <div class="profile_info" style="text-align: left">
                        <span>@if($user_info->is_supervisor==1)@if($user_info->unit_id!=4) مدیر  @else کارمند@endif @endif{{$user->unit->description}}</span><br>
                        <h2>{{$user_info->title}} {{$user_info->name}} {{$user_info->family}}</h2>
                    </div>

                </div>
                <!-- /menu profile quick info -->

                <br/>

                <!-- sidebar menu -->
                <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                    <div class="menu_section" style="margin-bottom:10px;">
                        <h3 style="font-size: 16px;"><a href="" onclick="alert('بزودی!')" style="color:white">پروفایل</a></h3>
                        <ul class="nav side-menu">
                            {{--//system manager menu--}}
                         
                            @if($user->is_supervisor==1 and $user->unit_id==3)
                                <li><a><i class="fa fa-pencil-square-o"></i> مدیریت امضاء ها<span
                                                class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu" style="display: none">
                                        <li><a href="{{url('systemManager/signaturesList')}}"> لیست امضاء ها</a>
                                        </li>
                                        <li><a href="{{url('systemManager/add_signature')}}">درج امضاء جدید </a>
                                        </li>
                                    </ul>
                                </li>
  
                            @endif
                            {{--//End system manager menu <br>--}}
                            {{--//Admin menu--}}
                            {{--@if($user->is_supervisor==1 and $user->unit_id==9)--}}
                                {{--<li><a href="{{url('/admin/issueBillManagement')}}"><i class="fa fa-credit-card"></i> صدور قبض انبار</a>--}}
                                {{--</li>--}}
                            {{--@endif--}}
                            @if($user->is_supervisor==1 and $user->unit_id==6)
                                <li><a><i class="fa fa-list"></i>ثبت درخواست های کاغذی<span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu" style="display: none">
                                        <li><a href="{{url('/special/productRequest')}}"> ثبت درخواست کالا</a></li>
                                        <li><a href="{{url('/special/serviceRequest')}}"> ثبت درخواست خدمت</a></li>
                                    </ul>
                                </li>
                            @endif
                            @if($user->is_supervisor==1 and $user->unit_id!=3)
                                <li><a><i class="fa fa-dropbox"></i>مدیریت درخواست کالا<span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu" style="display: none">
                                        <li><a href="{{url('/admin/productRequestManagement')}}"> درخواست های جدید</a></li>
                                        @if($user->is_supervisor==1)
                                            <li><a href="{{url('/admin/acceptProductRequestManagement')}}">بررسی شده</a></li>
                                            {{--                                    <li><a href="{{url('/admin/refusedProductRequestManagement')}}"> رد شده ها</a></li>--}}
                                            @if($user->is_supervisor==1 and $user->unit_id==6)
                                                <li><a href="{{url('/admin/confirmProductRequestManagement')}}">تایید شده</a></li>
                                            @endif
                                        @endif
                                    </ul>
                                </li>
                                <li><a><i class="fa fa-edit"></i> مدیریت درخواست خدمت<span
                                                class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu" style="display: none">
                                        <li><a href="{{url('/admin/serviceRequestManagement')}}"> درخواست های جدید</a></li>
                                        @if($user->is_supervisor==1)
                                            <li><a href="{{url('/admin/acceptServiceRequestManagement')}}">بررسی شده</a></li>
                                            {{--<li><a href="{{url('/admin/refusedProductRequestManagement')}}"> رد شده ها</a></li>--}}
                                            @if($user->is_supervisor==1 and $user->unit_id==6)
                                                <li><a href="{{url('/admin/confirmServiceRequestManagement')}}">تایید شده</a></li>
                                            @endif
                                        @endif
                                    </ul>
                                </li>
                            @endif
                            {{-- Rayat Start --}}
                            @if($user->is_supervisor==1 and $user->unit_id==3)
                                <li><a><i class="fa fa-users"></i> مدیریت کاربران <span
                                                class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu" style="display: none">
                                        <li><a href="{{url('admin/usersManagement')}}">مدیریت کاربران</a>
                                        </li>
                                        <li><a href="{{url('admin/usersCreate')}}">درج کاربر جدید</a>
                                        </li>
                                    </ul>
                                </li>
                                <li><a><i class="fa fa-th-list"></i> مدیریت واحدها <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu" style="display: none">
                                        <li><a href="{{url('admin/unitsManage')}}">مدیریت واحد ها</a>
                                        </li>
                                        <li><a href="{{url('admin/unitsCreate')}}">درج واحد جدید</a>
                                        </li>
                                    </ul>
                                </li>
                            @endif
                            @if($user->is_supervisor==1 and $user->unit_id==6)
                                <li><a><i class="fa fa-address-card"></i> مدیریت کارت های کارگری<span
                                                class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu" style="display: none">
                                        <li><a href="{{url('admin/workerCardCreate')}}">آپلود کارت کارگری</a>
                                        </li>
                                        <li><a href="{{url('admin/workerCardManage')}}">کارتهای کارگری صادر شده</a>
                                        </li>
                                    </ul>
                                </li>
                            @endif
                            @if(($user->is_supervisor==1 and $user->unit_id==6) or ($user->is_supervisor==1 and $user->unit_id==3))


                            <li><a><i class="fa fa-envelope-open-o"></i> مدیریت تیکت ها <span
                                            class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu" style="display: none">
                                    <li><a href="{{url('user/ticketRequest')}}">ارسال تیکت</a>
                                    <li><a href="{{url('user/ticketsManagement/1')}}">مشاهده تیکت های ارسالی</a> </li>
                                    <li><a href="{{url('user/ticketsManagement/2')}}">مشاهده تیکت های دریافتی</a> </li>
                                    {{--</li>--}}
                                </ul>
                            </li>
                            {{--<li><a><i class="fa fa-envelope"></i> صندوق پیام<span class="fa fa-chevron-down"></span></a>--}}

                                {{--<ul class="nav child_menu" style="display: none">--}}
                                {{--<li><a href="{{url('admin/')}}">پیام های دریافتی</a>--}}
                                {{--</li>--}}
                                {{--<li><a href="{{url('admin/')}}">پیام های ارسالی</a>--}}
                                {{--</li>--}}
                                {{--<li><a href="{{url('admin/')}}">پیام های دریافتی</a>--}}
                                {{--</li>--}}
                                {{--<li><a href="{{url('admin/')}}">سطل زباله پیام ها</a>--}}
                                {{--</li>--}}
                                {{--</ul>--}}
                                {{--</li>--}}
                                {{--<li><a><i class="fa fa-cogs"></i>تنظیمات<span class="fa fa-chevron-down"></span></a>--}}
                                {{--</li>--}}
                            @endif
                            {{--//End Admin menu <br>--}}
                            {{--//User menu--}}

                            {{-- user dashboard menu --}}


                            <li><a><i class="fa fa-dropbox "></i> درخواست کالا <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu" style="display: none">
                                    <li><a href="{{url('user/productRequest')}}">ارسال درخواست کالا</a>
                                    </li>
                                    <li><a href="{{url('user/productRequestFollow')}}">پیگیری درخواست کالا</a>
                                    </li>
                                </ul>
                            </li>
                            <li><a><i class="fa fa-edit"></i>درخواست خدمت <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu" style="display: none">
                                    <li><a href="{{url('user/serviceRequest')}}">ارسال درخواست خدمت</a>
                                    </li>
                                    <li><a href="{{url('user/serviceRequestFollow')}}">پیگیری درخواست خدمت</a>
                                    </li>
                                </ul>
                           </li>
                            @if($user->unit_id==6 and $user->is_supervisor!=1)
                            <li><a><i class="fa fa-odnoklassniki"></i>امور روزانه کارپرداز<span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu" style="display: none">
                                    <li><a href="{{url('user/dailyWorks/request')}}">امور مربوط به درخواست ها</a>
                                    <li><a href="{{url('user/dailyWorks/factors')}}">امور مربوط به خلاصه تنظیمی</a>
                                    </li>
                                </ul>
                            </li>
                            @endif
{{--                            @if(($user->is_supervisor==1 or $user->is_supervisor==0) and $user->unit_id!=3)--}}

                            <li><a><i class="fa fa-newspaper-o"></i> مدیریت گواهی ها<span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu" style="display: none">
                                    <li><a href="{{url('admin/productCertificatesManagement')}}"> تایید گواهی های کالا<span></span></a>
                                    </li>
                                    <li><a href="{{url('admin/serviceCertificatesManagement')}}"> تایید گواهی های خدمت<span></span></a>
                                    </li>
                                    <li><a href="{{url('admin/acceptedCertificatesManagement')}}"> وضعیت گواهی های تایید شده</a>
                                    </li>
                                </ul>
                            </li>

                            @if( ($user->is_supervisor == 1 and $user->unit_id !=3) and ($user->is_supervisor == 1 and $user->unit_id !=6 ) or $user->is_supervisor == 0 )
                                <li><a><i class="fa fa-envelope-open-o"></i>ارسال تیکت<span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu" style="display: none">
                                        <li><a href="{{url('user/ticketRequest')}}">ارسال تیکت</a>
                                        </li>
                                        <li><a href="{{url('user/ticketsManagement/1')}}">پیگیری تیکت</a>
                                        </li>
                                    </ul>
                                </li>
                            @endif
                                {{--<li><a><i class="fa fa-envelope"></i> صندوق پیام<span class="fa fa-chevron-down"></span></a>--}}
                                {{--<ul class="nav child_menu" style="display: none">--}}
                                {{--<li><a href="#">صندوق پیام</a>--}}
                                {{--</li>--}}
                                {{--<li><a href="#">پیام های ارسالی</a>--}}
                                {{--</li>--}}
                                {{--<li><a href="#">پیام های دریافتی</a>--}}
                                {{--</li>--}}
                                {{--<li><a href="#">سطل زباله پیام ها</a>--}}
                                {{--</li>--}}
                                {{--</ul>--}}
                                {{--</li>--}}

                            {{--//End User menu--}}
                            {{-- end user dashboard menu --}}
                           {{--<a class="btn btn-default col-md-12" style="color:white;" href="{{url('/logout')}}">خروج</a>--}}

                            {{--<li style="font-size:20px;"><a style="background-color: rgba(231, 76, 60, 0.88);padding:3px 10px 3px 0" href="{{url('/logout')}}"><i class="fa fa-sign-out" style="margin-left:20px;"></i>خروج</a>--}}
                            {{--</li>--}}
                            <li><a href={{url('user/changePassword')}}><i class="fa fa-key"></i> تغییر رمز عبور</a>
                            </li>
                            <li style="background-color: rgba(231, 76, 60, 0.88)"><a href="{{url('/logout')}}"><i class="fa fa-sign-out"></i> خروج</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- /sidebar menu -->
                {{--<!-- /menu footer buttons -->--}}
                {{--<div class="sidebar-footer hidden-small">--}}
                {{--<a data-toggle="tooltip" data-placement="top" title="تنظیمات">--}}
                {{--<span class="glyphicon glyphicon-cog" aria-hidden="true"></span>--}}
                {{--</a>--}}
                {{--<a data-toggle="tooltip" data-placement="top" title="بزرگ کردن صفحه">--}}
                {{--<span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>--}}
                {{--</a>--}}
                {{--<a data-toggle="tooltip" data-placement="top" title="قفل کردن">--}}
                {{--<span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>--}}
                {{--</a>--}}
                {{--<a data-toggle="tooltip" data-placement="top" title="خروج">--}}
                {{--<span class="glyphicon glyphicon-off" aria-hidden="true"></span>--}}
                {{--</a>--}}
                {{--</div>--}}
                {{--<!-- /menu footer buttons -->--}}
            </div>
        </div>
        <!-- top navigation -->
        <div class="top_nav">

            <div class="nav_menu">
                <nav class="" role="navigation">
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                    </div>
                    <div class="" style="float: right;padding: 1% 2% 0 0 !important;">
                        <a id="back" class="btn btn-info">بازگشت به صفحه قبل</a>
                    </div>

                </nav>
            </div>

        </div>
        <!-- /top navigation -->
        <!-- page content -->
        <div class="right_col" role="main">
            <!-- top tiles -->
            <!-- /top tiles -->
            @yield('content')
        </div>
        <!-- footer content -->
        <footer>
            <div class="copyright-info">
                <p class="pull-right"> کلیه حقوق این پورتال متعلق به شبکه بهداشت خمینی شهر است<a
                            href="https://colorlib.com"></a>
                </p>
            </div>
            <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
    </div>
    <!-- /page content -->
</div>

<div id="custom_notifications" class="custom-notifications dsp_none">
    <ul class="list-unstyled notifications clearfix" data-tabbed_notifications="notif-group">
    </ul>
    <div class="clearfix"></div>
    <div id="notif-group" class="tabbed_notifications"></div>
</div>
<script src="{{ URL::asset('public/dashboard/js/bootstrap.min.js')}}"></script>
<script src="{{ URL::asset('public/dashboard/js/nicescroll/jquery.nicescroll.min.js')}}"></script>

<!-- bootstrap progress js -->
<script src="{{ URL::asset('public/dashboard/js/progressbar/bootstrap-progressbar.min.js')}}"></script>
<!-- icheck -->
<script src="{{ URL::asset('public/dashboard/js/icheck/icheck.min.js')}}"></script>
<!-- daterangepicker -->
<script type="text/javascript" src="{{ URL::asset('public/dashboard/js/moment/moment.min.js')}}"></script>
<script type="text/javascript" src="{{ URL::asset('public/dashboard/js/datepicker/daterangepicker.js')}}"></script>
<!-- chart js -->
<script src="{{ URL::asset('public/dashboard/js/chartjs/chart.min.js')}}"></script>
<!-- sparkline -->
<script src="{{ URL::asset('public/dashboard/js/sparkline/jquery.sparkline.min.js')}}"></script>

<script src="{{URL::asset('public/js/sweetalert.min.js')}}"></script>
<script src="{{ URL::asset('public/js/serviceRequest.js')}}"></script>
{{--<script src="{{ URL::asset('public/js/serviceShowDetails.js')}}"></script>--}}
<script src="{{ URL::asset('public/dashboard/js/custom.js')}}"></script>

<!-- image cropping -->
<script src="{{ URL::asset('public/dashboard/js/cropping/cropper.min.js')}}"></script>
<script src="{{ URL::asset('public/dashboard/js/cropping/main.js')}}"></script>

<!-- flot js -->
<!--[if lte IE 8]>
<script type="text/javascript" src="{{ URL::asset('public/dashboard/js/excanvas.min.js')}}"></script><![endif]-->
<script type="text/javascript" src="{{ URL::asset('public/dashboard/js/flot/jquery.flot.js')}}"></script>
<script type="text/javascript" src="{{ URL::asset('public/dashboard/js/flot/jquery.flot.pie.js')}}"></script>
<script type="text/javascript" src="{{ URL::asset('public/dashboard/js/flot/jquery.flot.orderBars.js')}}"></script>
<script type="text/javascript" src="{{ URL::asset('public/dashboard/js/flot/jquery.flot.time.min.js')}}"></script>
<script type="text/javascript" src="{{ URL::asset('public/dashboard/js/flot/date.js')}}"></script>
<script type="text/javascript" src="{{ URL::asset('public/dashboard/js/flot/jquery.flot.spline.js')}}"></script>
<script type="text/javascript" src="{{ URL::asset('public/dashboard/js/flot/jquery.flot.stack.js')}}"></script>
<script type="text/javascript" src="{{ URL::asset('public/dashboard/js/flot/curvedLines.js')}}"></script>
<script type="text/javascript" src="{{ URL::asset('public/dashboard/js/flot/jquery.flot.resize.js')}}"></script>
{{--<script src="{{ URL::asset('public/js/kianfar.js')}}"></script>--}}

{{--My Jqyert Code--}}
<script type="text/javascript" src="{{ URL::asset('public/dashboard/js/myCode.js')}}"></script>
<!-- Datatables-->
<script src="{{ URL::asset('public/dashboard/js/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ URL::asset('public/dashboard/js/datatables/dataTables.bootstrap.js')}}"></script>
<script src="{{ URL::asset('public/dashboard/js/datatables/dataTables.buttons.min.js')}}"></script>
<script src="{{ URL::asset('public/dashboard/js/datatables/buttons.bootstrap.min.js')}}"></script>
<script src="{{ URL::asset('public/dashboard/js/datatables/jszip.min.js')}}"></script>
<script src="{{ URL::asset('public/dashboard/js/datatables/pdfmake.min.js')}}"></script>
<script src="{{ URL::asset('public/dashboard/js/datatables/vfs_fonts.js')}}"></script>
<script src="{{ URL::asset('public/dashboard/js/datatables/buttons.html5.min.js')}}"></script>
<script src="{{ URL::asset('public/dashboard/js/datatables/buttons.print.min.js')}}"></script>
<script src="{{ URL::asset('public/dashboard/js/datatables/dataTables.fixedHeader.min.js')}}"></script>
<script src="{{ URL::asset('public/dashboard/js/datatables/dataTables.keyTable.min.js')}}"></script>
<script src="{{ URL::asset('public/dashboard/js/datatables/dataTables.responsive.min.js')}}"></script>
<script src="{{ URL::asset('public/dashboard/js/datatables/responsive.bootstrap.min.js')}}"></script>
<script src="{{ URL::asset('public/dashboard/js/datatables/dataTables.scroller.min.js')}}"></script>
<link rel="stylesheet" href="{{URL::asset('public/css/persianDatepicker-default.css')}}" />


<script type="text/javascript">
    $(document).ready(function () {
        $('#datatable').dataTable();
        $('#datatable-keytable').DataTable({
            keys: true,
            bSort: false
        });
        $('#datatable-responsive').DataTable();
        $('#datatable-scroller').DataTable({
            ajax: "{{URL::asset('public/dashboard/js/datatables/json/scroller-demo.json')}}",
            deferRender: true,
            scrollY: 380,
            scrollCollapse: true,
            scroller: true,
            bSort: false
        });
        var table = $('#datatable-fixed-header').DataTable({
            fixedHeader: true,
            bSort: false

        });
    });
    TableManageButtons.init();
</script>
<!-- pace -->
<script src="{{ URL::asset('public/dashboard/js/pace/pace.min.js')}}"></script>
{{--User passChange--}}
<script>
    $(document).ready(function () {
        $('#example').DataTable({
            "pageLength": 10,
            bSort: false,
            initComplete: function () {
                this.api().columns([0, 1, 3, 4]).every(function () {
                    var column = this;
                    var select = $('<select><option value=""></option></select>')
                        .appendTo($(column.footer()).empty())
                        .on('change', function () {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );
                            column
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                        });
                    column.data().unique().sort().each(function (d, j) {
                        select.append('<option value="' + d + '">' + d + '</option>')
                    });
                });
            }
        });
    });
</script>
<script src="{{URL::asset('public/dashboard/js/dropzone/dropzone.js')}}"></script>
<!-- PNotify -->
<script type="text/javascript" src="{{ URL::asset('public/dashboard/js/notify/pnotify.core.js')}}"></script>
<script type="text/javascript" src="{{ URL::asset('public/dashboard/js/notify/pnotify.buttons.js')}}"></script>
<script type="text/javascript" src="{{ URL::asset('public/dashboard/js/notify/pnotify.nonblock.js')}}"></script>


<!-- richtext editor -->
<script src="{{URL::asset('public/dashboard/js/editor/bootstrap-wysiwyg.js')}}"></script>
<script src="{{URL::asset('public/dashboard/js/editor/external/jquery.hotkeys.js')}}"></script>
<script src="{{URL::asset('public/dashboard/js/editor/external/google-code-prettify/prettify.js')}}"></script>


<!-- editor -->
<script>
    $(document).ready(function () {
        $('.xcxc').click(function () {
            $('#descr').val($('#editor').html());
        });
    });
    $(function () {
        function initToolbarBootstrapBindings() {
            var fonts = ['Serif', 'Sans', 'Arial', 'Arial Black', 'Courier',
                    'Courier New', 'Comic Sans MS', 'Helvetica', 'Impact', 'Lucida Grande', 'Lucida Sans', 'Tahoma', 'Times',
                    'Times New Roman', 'Verdana'
                ],
                fontTarget = $('[title=Font]').siblings('.dropdown-menu');
            $.each(fonts, function (idx, fontName) {
                fontTarget.append($('<li><a data-edit="fontName ' + fontName + '" style="font-family:\'' + fontName + '\'">' + fontName + '</a></li>'));
            });
            $('a[title]').tooltip({
                container: 'body'
            });
            $('.dropdown-menu input').click(function () {
                return false;
            })
                .change(function () {
                    $(this).parent('.dropdown-menu').siblings('.dropdown-toggle').dropdown('toggle');
                })
                .keydown('esc', function () {
                    this.value = '';
                    $(this).change();
                });
            $('[data-role=magic-overlay]').each(function () {
                var overlay = $(this),
                    target = $(overlay.data('target'));
                overlay.css('opacity', 0).css('position', 'absolute').offset(target.offset()).width(target.outerWidth()).height(target.outerHeight());
            });
            if ("onwebkitspeechchange" in document.createElement("input")) {
                var editorOffset = $('#editor').offset();
                $('#voiceBtn').css('position', 'absolute').offset({
                    top: editorOffset.top,
                    left: editorOffset.left + $('#editor').innerWidth() - 35
                });
            } else {
                $('#voiceBtn').hide();
            }
        };
        function showErrorAlert(reason, detail) {
            var msg = '';
            if (reason === 'unsupported-file-type') {
                msg = "Unsupported format " + detail;
            } else {
                console.log("error uploading file", reason, detail);
            }
            $('<div class="alert"> <button type="button" class="close" data-dismiss="alert">&times;</button>' +
                '<strong>File upload error</strong> ' + msg + ' </div>').prependTo('#alerts');
        };
        initToolbarBootstrapBindings();
        $('#editor').wysiwyg({
            fileUploadError: showErrorAlert
        });
        window.prettyPrint && prettyPrint();
    });
</script>
<!-- /editor -->
<script>
    // initialize the validator function
    validator.message['date'] = 'not a real date';
    // validate a field on "blur" event, a 'select' on 'change' event & a '.reuired' classed multifield on 'keyup':
    $('form')
        .on('blur', 'input[required], input.optional, select.required', validator.checkField)
        .on('change', 'select.required', validator.checkField)
        .on('keypress', 'input[required][pattern]', validator.keypress);
    $('.multi.required')
        .on('keyup blur', 'input', function () {
            validator.checkField.apply($(this).siblings().last()[0]);
        });
    // bind the validation to the form submit event
    //$('#send').click('submit');//.prop('disabled', true);
    $('form').submit(function (e) {
        e.preventDefault();
        var submit = true;
        // evaluate the form using generic validaing
        if (!validator.checkAll($(this))) {
            submit = false;
        }
        if (submit)
            this.submit();
        return false;
    });
    /* FOR DEMO ONLY */
    $('#vfields').change(function () {
        $('form').toggleClass('mode2');
    }).prop('checked', false);
    $('#alerts').change(function () {
        validator.defaults.alerts = (this.checked) ? false : true;
        if (this.checked)
            $('form .alert').remove();
    }).prop('checked', false);
</script>
<script>
    NProgress.done();
</script>
<!-- /datepicker -->
<!-- /footer content -->
{{--<script>--}}
{{--function unit_count() {--}}
{{--$.ajax({--}}
{{--url: "{{ url('/unit_count') }}",--}}
{{--type: 'GET',--}}
{{--dataType: 'json',--}}
{{--success: function (response) {--}}
{{--var html;--}}
{{--$.each(response.unit_counts, function (index, value) {--}}
{{--html += '<option value="' + value + '">' + value['title'] + '</option>';--}}
{{--});--}}
{{--$("#unit_count").html(html);--}}
{{--},--}}
{{--error: function (error) {--}}
{{--var errors = error.responseJSON;--}}
{{--console.log(errors);--}}
{{--}--}}
{{--});--}}
{{--}--}}
{{--var record_count = 0;--}}
{{--function unit_count_each_record(select_id) {--}}
{{--$.ajax({--}}
{{--url: "{{ url('/unit_count') }}",--}}
{{--type: 'GET',--}}
{{--dataType: 'json',--}}
{{--success: function (response) {--}}
{{--var html;--}}
{{--$.each(response.unit_counts, function (index, value) {--}}
{{--//                   html += '<option value="' + value + '">' +  value['title'] + '</option>';--}}
{{--html += '<option value="' + value + '">' + value['title'] + '</option>';--}}
{{--});--}}
{{--$("#" + select_id).html(html);--}}
{{--},--}}
{{--error: function (error) {--}}
{{--var errors = error.responseJSON;--}}
{{--console.log(errors);--}}
{{--}--}}
{{--});--}}
{{--}--}}
{{--</script>--}}
<script>
    $(document).ready(function () {
        $('#back').click(function () {
            window.history.back();
        });
    });
</script>
</body>
</html>