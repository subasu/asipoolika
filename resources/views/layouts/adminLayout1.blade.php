<!DOCTYPE html>
<html lang="en">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<!-- Meta, title, CSS, favicons, etc. -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">
<title></title>

<!-- Bootstrap core CSS -->

<link href="{{ URL::asset('public/dashboard/css/bootstrap.min.css')}}" rel="stylesheet">
<link href="{{ URL::asset('public/dashboard/fonts/css/font-awesome.min.css')}}" rel="stylesheet">
<link href="{{ URL::asset('public/dashboard/css/animate.min.css')}}" rel="stylesheet">
{{--My Style Code--}}
<link href="{{ URL::asset('public/dashboard/css/mystyle.css')}}" rel="stylesheet">

<!-- Custom styling plus plugins -->
<link href="{{ URL::asset('public/dashboard/css/custom.css')}}" rel="stylesheet">
<link rel="stylesheet" type="text/css"
      href="{{ URL::asset('public/dashboard/css/maps/jquery-jvectormap-2.0.3.css')}}"/>
<link href="{{ URL::asset('public/dashboard/css/icheck/flat/green.css')}}" rel="stylesheet"/>
<link href="{{ URL::asset('public/dashboard/css/floatexamples.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{URL::asset('public/panel/sweetalert/sweetalert.css')}}" rel="stylesheet"/>
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
<!--End Data table-->

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
<body class="nav-md">
<div class="container body">
    <div class="main_container">
        <div class="col-md-3 left_col">
            <div class="left_col scroll-view">

                <div class="navbar nav_title" style="border: 0;">
                    <a href="index.html" class="site_title"><span>پنل مدیریتی </span></a>
                </div>
                <div class="clearfix"></div>

                <!-- menu prile quick info -->
                <div class="profile">
                    <div class="profile_pic">
                        <img src="{{URL::asset('public/dashboard/images/img.jpg')}}" alt="..."
                             class="img-circle profile_img">
                    </div>
                    <div class="profile_info">
                        <span>آقای/خانم</span>
                        <h2>عاطفه کیانفر</h2>
                    </div>
                </div>
                <!-- /menu prile quick info -->

                <br/>

                <!-- sidebar menu -->
                <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                    <div class="menu_section">
                        <h3>کاربر عمومی</h3>
                        <ul class="nav side-menu">
                            <li><a><i class="fa fa-home"></i> مدیر سیستم<span></span></a>
                            </li>
                            <li><a><i class="fa fa-edit"></i> مدیریت امضاء ها<span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu" style="display: none">
                                    <li><a href="{{url('systemManager/signatures')}}"> لیست امضاء ها</a>
                                    </li>
                                    <li><a href="{{url('systemManager/add_signature')}}">درج امضاء جدید </a>
                                    </li>
                                </ul>
                            </li>


                            <li><a><i class="fa fa-home"></i> داشبورد <span></span></a>
                            </li>
                            <li><a><i class="fa fa-edit"></i>مدیریت درخواست ها <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu" style="display: none">
                                    <li><a href="form.html">درخواست کالا</a>
                                    </li>
                                    <li><a href="form.html">درخواست خدمت</a>
                                    </li>
                                    <li><a href="form.html">درخواست های انجام شده</a>
                                    </li>
                                </ul>
                            </li>
                            <li><a><i class="fa fa-th-list"></i>واحد جدید<span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu" style="display: none">
                                    <li><a href="general_elements.html">ارسال درخواست خدمت</a>
                                    </li>
                                </ul>
                            </li>
                            <li><a><i class="fa fa-user"></i>کاربر جدید<span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu" style="display: none">
                                    <li><a href="tables.html">ارسال درخواست تشریفات</a>
                                    </li>
                                </ul>
                            </li>
                            <li><a><i class="fa fa-cogs"></i>تنظیمات<span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu" style="display: none">
                                    <li><a href="tables.html">ارسال درخواست تشریفات</a>
                                    </li>
                                </ul>
                            </li>
                            <li><a><i class="fa fa-envelope"></i> مدیریت تیکت ها <span
                                            class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu" style="display: none">
                                    <li><a href="chartjs.html">ارسال تیکت</a>
                                    </li>
                                    <li><a href="chartjs.html">تیکت های ارسال شده</a>
                                    </li>
                                </ul>
                            </li>

                            {{-- user dashboard menu --}}
                            <li><a><i class="fa fa-dropbox "></i> درخواست کالا <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu" style="display: none">
                                    <li><a href="form.html">ارسال درخواست کالا</a>
                                    </li>
                                    <li><a href="form.html">پیگیری درخواست کالا</a>
                                    </li>
                                </ul>
                            </li>
                            <li><a><i class="fa fa-edit"></i>درخواست خدمت <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu" style="display: none">
                                    <li><a href="general_elements.html">ارسال درخواست خدمت</a>
                                    </li>
                                    <li><a href="general_elements.html">پیگیری درخواست خدمت</a>
                                    </li>
                                </ul>
                            </li>
                            <li><a><i class="fa fa-file"></i> درخواست تشریفات <span
                                            class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu" style="display: none">
                                    <li><a href="tables.html">ارسال درخواست تشریفات</a>
                                    </li>
                                    <li><a href="tables.html">پیگیری درخواست تشریفات</a>
                                    </li>
                                </ul>
                            </li>
                            <li><a><i class="fa fa-envelope"></i> مدیریت تیکت ها <span
                                            class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu" style="display: none">
                                    <li><a href="chartjs.html">ارسال تیکت</a>
                                    </li>
                                    <li><a href="chartjs.html">تیکت های ارسال شده</a>
                                    </li>
                                </ul>
                            </li>
                            {{-- end user dashboard menu --}}
                        </ul>
                    </div>
                </div>
                <!-- /sidebar menu -->
                <!-- /menu footer buttons -->
                <div class="sidebar-footer hidden-small">
                    <a data-toggle="tooltip" data-placement="top" title="تنظیمات">
                        <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                    </a>
                    <a data-toggle="tooltip" data-placement="top" title="بزرگ کردن صفحه">
                        <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
                    </a>
                    <a data-toggle="tooltip" data-placement="top" title="قفل کردن">
                        <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
                    </a>
                    <a data-toggle="tooltip" data-placement="top" title="خروج">
                        <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                    </a>
                </div>
                <!-- /menu footer buttons -->
            </div>
        </div>
        <!-- top navigation -->
        <div class="top_nav">

            <div class="nav_menu">
                <nav class="" role="navigation">
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                    </div>

                    <ul class="nav navbar-nav navbar-right">
                        <li class="">
                            <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown"
                               aria-expanded="false">
                                <img src="{{url('public/dashboard/images/img.jpg')}}" alt="">John Doe
                                <span class=" fa fa-angle-down"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-usermenu animated fadeInDown pull-right">
                                <li><a href="javascript:;"> Profile</a>
                                </li>
                                <li>
                                    <a href="javascript:;">
                                        <span class="badge bg-red pull-right">50%</span>
                                        <span>Settings</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:;">Help</a>
                                </li>
                                <li><a href="login.html"><i class="fa fa-sign-out pull-right"></i> Log Out</a>
                                </li>
                            </ul>
                        </li>

                        <li role="presentation" class="dropdown">
                            <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown"
                               aria-expanded="false">
                                <i class="fa fa-envelope-o"></i>
                                <span class="badge bg-green">6</span>
                            </a>
                            <ul id="menu1" class="dropdown-menu list-unstyled msg_list animated fadeInDown" role="menu">
                                <li>
                                    <a>
                      <span class="image">
                                        <img src="{{URL::asset('public/dashboard/images/img.jpg')}}"
                                             alt="Profile Image"/>
                                    </span>
                                        <span>
                                        <span>John Smith</span>
                      <span class="time">3 mins ago</span>
                      </span>
                                        <span class="message">
                                        Film festivals used to be do-or-die moments for movie makers. They were where...
                                    </span>
                                    </a>
                                </li>
                                <li>
                                    <a>
                      <span class="image">
                                        <img src="{{URL::asset('public/dashboard/images/img.jpg')}}"
                                             alt="Profile Image"/>
                                    </span>
                                        <span>
                                        <span>John Smith</span>
                      <span class="time">3 mins ago</span>
                      </span>
                                        <span class="message">
                                        Film festivals used to be do-or-die moments for movie makers. They were where...
                                    </span>
                                    </a>
                                </li>
                                <li>
                                    <a>
                      <span class="image">
                                        <img src="{{URL::asset('public/dashboard/images/img.jpg')}}"
                                             alt="Profile Image"/>
                                    </span>
                                        <span>
                                        <span>John Smith</span>
                      <span class="time">3 mins ago</span>
                      </span>
                                        <span class="message">
                                        Film festivals used to be do-or-die moments for movie makers. They were where...
                                    </span>
                                    </a>
                                </li>
                                <li>
                                    <a>
                      <span class="image">
                                        <img src="{{URL::asset('public/dashboard/images/img.jpg')}}"
                                             alt="Profile Image"/>
                                    </span>
                                        <span>
                                        <span>John Smith</span>
                      <span class="time">3 mins ago</span>
                      </span>
                                        <span class="message">
                                        Film festivals used to be do-or-die moments for movie makers. They were where...
                                    </span>
                                    </a>
                                </li>
                                <li>
                                    <div class="text-center">
                                        <a href="inbox.html">
                                            <strong>See All Alerts</strong>
                                            <i class="fa fa-angle-right"></i>
                                        </a>
                                    </div>
                                </li>
                            </ul>
                        </li>

                    </ul>
                </nav>
            </div>

        </div>
        <!-- /top navigation -->
        <!-- page content -->
        <div class="right_col" role="main">
            <!-- top tiles -->
            <!-- /top tiles -->
            @yield('content')
                    <!-- footer content -->
            <footer>
                <div class="copyright-info">
                    <p class="pull-left">Supplies - All reserved By Artan Group <a href="https://colorlib.com"></a>
                    </p>
                </div>
                <div class="clearfix"></div>
            </footer>
            <!-- /footer content -->
        </div>
        <!-- /page content -->
    </div>
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

<script src="{{ URL::asset('public/dashboard/js/custom.js')}}"></script>
<script src="{{ URL::asset('public/js/kianfar.js')}}"></script>

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
<script type="text/javascript">
    $(document).ready(function () {
        unit_count();
        $('#datatable').dataTable();
        $('#datatable-keytable').DataTable({
            keys: true
        });
        $('#datatable-responsive').DataTable();
        $('#datatable-scroller').DataTable({
            ajax: "{{URL::asset('public/dashboard/js/datatables/json/scroller-demo.json')}}",
            deferRender: true,
            scrollY: 380,
            scrollCollapse: true,
            scroller: true
        });
        var table = $('#datatable-fixed-header').DataTable({
            fixedHeader: true
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
            "pageLength": 5,
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
{{--kianfar--}}

<script>
    function unit_count() {
        $.ajax({
            url: "{{ url('/unit_count') }}",
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                var html;
                $.each(response.unit_counts, function (index, value) {
                    html += '<option value="' + value + '">' + value['title'] + '</option>';
                });
                $("#unit_count").html(html);
            },
            error: function (error) {
                var errors = error.responseJSON;
                console.log(errors);
            }
        });
    }
    var record_count = 0;
    function unit_count_each_record(select_id) {
        $.ajax({
            url: "{{ url('/unit_count') }}",
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                var html;
                $.each(response.unit_counts, function (index, value) {
//                   html += '<option value="' + value + '">' +  value['title'] + '</option>';
                    html += '<option value="' + value + '">' + value['title'] + '</option>';
                });
                $("#" + select_id).html(html);
            },
            error: function (error) {
                var errors = error.responseJSON;
                console.log(errors);
            }
        });
    }
</script>
<script src="{{URL::asset('public/panel/sweetalert/sweetalert-dev.js')}}"></script>
<script>
    $('#save_request').click(function(){
        swal({
                    title: "آیا از ثبت درخواست مطمئن هستید؟",
                    text: "",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "	#5cb85c",
                    cancelButtonText: "خیر ، منصرف شدم",
                    confirmButtonText: "بله ثبت شود",
                    closeOnConfirm: false,
                    closeOnCancel: false
                },
                function(isConfirm){
                    if (isConfirm) {
                        //serialize() send all form input values
                        var formData = $('#product').serialize();
//                        console.log(formData);
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                            }
                        });
                        $.ajax({
                            url: "{{ url('user/productRequest') }}",
                            type: 'POST',
                            dataType: 'json',
                            data: formData,
                            success: function (response) {
                                swal('درخواست ثبت شد','درخواست به لیست درخواست های شما اضافه شد','success');
                            },
                            error: function (error) {
                                if (error.status === 422) {
                                    $errors = error.responseJSON; //this will get the errors response data.
                                    //show them somewhere in the markup
                                    //e.g
                                    var errorsHtml = '<div id="alert_div" class="alert alert-danger col-md-12 col-sm-12 col-xs-12" style="text-align:right;padding-right:10%;margin-bottom:-4%" role="alert"><ul>';
//
                                    $.each($errors, function (key, value) {
                                        errorsHtml += '<li>' + value[0] + '</li>'; //showing only the first error.
                                    });
                                    errorsHtml += '</ul></div>';
                                    $('fieldset').append(errorsHtml);
                                    swal("خطاهای زیر را برطرف کنید !", '', "error");
                                } else if (error.status === 500) {
                                    alert('خطایی رخ داده لطفا بعد از مدت زمانی مجددا تلاش کنید');
                                    console.log(error);
                                }
                            }
                        });
                    } else {
                        swal("منصرف شدید", "درخواست ثبت نشد", "error");
                    }
                });
    });
</script>

<script>
    $("#unit_signature").change(function() {
        var $this = $(this);
        var id = $this.val();
        $.ajax({
            url: "{{ url('unit_signature') }}",
            type: 'GET',
            dataType: 'json',
            data: {unit_id: id},
            success: function(response) {
                var html;
                html += '<option value="">صاحب امضاء را انتخاب کنید</option>';
                $.each(response.users, function(index, value) {
                    html += '<option value="' + value['id'] + '">' +  value['title']+' '+value['name']+' '+value['family'] + '</option>';
                });
                $("#user_signature").html(html);
            },
            error: function(error) {
                var errors = error.responseJSON;
                console.log(errors);
            }
        });
    });
</script>
</body>
</html>
