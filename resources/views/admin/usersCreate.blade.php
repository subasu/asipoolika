@extends('layouts.adminLayout')
@section('content')
    <style>
        .star {
            color: #ff0000;
            float: right;
            padding-right:4px;
            padding-left:4px;
        }

        input, label {
            font-size: 15px;
        }
    </style>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
            <div class="x_panel">
                <div class="x_title">
                    <h2> فرم ایجاد کاربر
                    </h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                {{-- table --}}
                <div class="col-md-3 col-sm-3 col-xs-12"></div>
                <div class="col-md-10 col-sm-6 col-xs-12 col-md-offset-1">
                    <div class="x_content">
                        <form class="form-horizontal form-label-left" id="user-send-form" method="POST"
                              style="direction: rtl !important;">
                            {{ csrf_field() }}
                            <div class="item form-group">
                                <div class="col-md-8 col-sm-6 col-xs-12">
                                    <select id="title" class="form-control col-md-7 col-xs-12" name="title">
                                        <option>دکتر</option>
                                        <option>مهندس</option>
                                        <option>آقای</option>
                                        <option>خانم</option>
                                    </select>

                                </div>
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="title"> عنوان : <span
                                            class="required star" title="پر کردن این فیلد الزامی است">*</span>
                                </label>
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="item form-group">
                                <div class="col-md-8 col-sm-6 col-xs-12">
                                    <input id="name" class="form-control col-md-7 col-xs-12" name="name" placeholder=""
                                           required="required" type="text">
                                </div>
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="name"> نام : <span
                                            class="required star" title="پر کردن این فیلد الزامی است">*</span>
                                </label>
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="item form-group {{ $errors->has('family') ? ' has-error' : '' }}">
                                <div class="col-md-8 col-sm-6 col-xs-12">
                                    <input id="family" class="form-control col-md-7 col-xs-12" name="family"
                                           placeholder="" required="required" type="text">
                                </div>
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="family"> نام خانوادگی :
                                    <span class="required star" title="پر کردن این فیلد الزامی است">*</span>
                                </label>
                                @if ($errors->has('family'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('family') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="item form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                <div class="col-md-8 col-sm-6 col-xs-12">
                                    <input type="email" id="email" name="email" required="required"
                                           class="form-control col-md-7 col-xs-12">
                                </div>
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="email"> ایمیل : <span
                                            class="required star" title="پر کردن این فیلد الزامی است">*</span>
                                </label>
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="item form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                                <div class="col-md-8 col-sm-6 col-xs-12">
                                    <input id="password" type="password" name="password"
                                           class="form-control col-md-7 col-xs-12" required="required">
                                </div>
                                <label for="password" class="control-label col-md-4">رمز عبور:
                                    <span class="required star" title="پر کردن این فیلد الزامی است">*</span></label>
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="item form-group">
                                <div class="col-md-8 col-sm-6 col-xs-12">
                                    <input id="password-confirm" type="password" class="form-control col-md-7 col-xs-12"
                                           name="password_confirmation" required>
                                </div>
                                <label for="password-confirm" class="control-label col-md-4 col-sm-4 col-xs-12"> تکرار
                                    رمز عبور:
                                    <span class="required star" title="پر کردن این فیلد الزامی است">*</span></label>
                            </div>
                            <div class="item form-group {{ $errors->has('cellphone') ? ' has-error' : '' }}">
                                <div class="col-md-8 col-sm-6 col-xs-12">
                                    <input type="tel" id="cellphone" name="cellphone" required="required"
                                           class="form-control col-md-7 col-xs-12">
                                </div>
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="cellphone">شماره موبایل :
                                    {{--<span class="required" title="پر کردن این فیلد الزامی است">*</span></label>--}}
                                @if ($errors->has('cellphone'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('cellphone') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="item form-group {{ $errors->has('internal_phone') ? ' has-error' : '' }}">
                                <div class="col-md-8 col-sm-6 col-xs-12">
                                    <input type="tel" id="	internal_phone" name="internal_phone" required="required"
                                           class="form-control col-md-7 col-xs-12">
                                </div>
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="internal_phone">تلفن داخلی :</label>
                                    {{--<span class="required" title="پر کردن این فیلد الزامی است">*</span></label>--}}
                                @if ($errors->has('internal_phone'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('internal_phone') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="item form-group">
                                <div class="col-md-8 col-sm-6 col-xs-12">
                                    <select class="form-control col-md-7 col-xs-12" name="unit_id" id="unit_id">
                                        <option value="">واحد مربوطه را انتخاب نمایید</option>
                                        @foreach($units as $unit)
                                            <option name="units" class="align-right"
                                                    value="{{$unit->id}}">{{$unit->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="unit_id"> واحد :
                                    <span class="required star" title="پر کردن این فیلد الزامی است">*</span></label>
                            </div>

                            {{--<div class="item form-group">--}}
                            {{--<div class="col-md-8 col-sm-6 col-xs-12">--}}
                            {{--<select class="form-control col-md-7 col-xs-12" name="supervisor_id"--}}
                            {{--id="supervisor_id">--}}
                            {{--<option name="supervisor" value="">ابتدا واحد را انتخاب نمایید</option>--}}
                            {{--</select>--}}
                            {{--</div>--}}
                            {{--<label class="control-label col-md-3 col-sm-3 col-xs-12" for="supervisor_id">سرپرست--}}
                            {{--<span class="required" title="پر کردن این فیلد الزامی است">*</span></label>--}}
                            {{--</div>--}}
                            <div class="item form-group">
                                <div class="col-md-8 col-sm-6 col-xs-12">

                                    <input class="col-md-1 col-sm-4 col-xs-12 pull-right " style="margin-top: 6%;" id="unitManager" name="unitManager" value="0" type="checkbox">
                                    <label class="control-label col-md-11 col-sm-4 col-xs-12" style="text-align: justify;">مدیر واحد</label>
                                </div>
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="supervisor_id">مدیر واحد
                                </label>
                            </div>
                            <div class="item form-group">
                                <div class="col-md-8 col-sm-6 col-xs-12">
                                    <textarea id="description" required="required" name="description"
                                              class="form-control col-md-7 col-xs-12"></textarea>
                                </div>
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="description">توضیحات :
                                </label>
                                @if ($errors->has('description'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="ln_solid"></div>
                            <div class="form-group">
                                <div class="col-md-8">
                                    <button id="user-send" type="button" class="col-md-12 btn btn-primary">ثبت</button>
                                    <input type="hidden" name="unitId" id="unitId" value="0">
                                    <input type="hidden" name="supervisorId" id="supervisorId" value="0">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-12"></div>
            </div>
        </div>


    {{--create user by AJAX and show result alert and redirect to usersList page --}}
    <script>
        $("#user-send").click(function () {


            var unitId = "";
            $("[name='units']:selected").each(function(){
                unitId +=$(this).val();
                $('#unitId').val(unitId);
            });
            //alert(unitId);

            var supervisorId = "";
            $("[name='supervisor']:selected").each(function () {
                supervisorId += $(this).val();
                $('#supervisorId').val(supervisorId);
            });

            var password = $('#password').val();
            var confirmPassword = $('#password-confirm').val();


            var formData = new FormData($('#user-send-form')[0]);

            $.ajax({

                cache : false,
                url: "{{URL::asset('admin/checkUnitSupervisor')}}",
                type: 'post',
                data: formData,
                contentType : false,
                processData : false,
                //dataType: 'json',
                beforeSend:function()
                {
                    if(password !== confirmPassword)
                    {
                        swal({
                            title: "",
                            text: 'رمزهای وارد شده با هم مطابقت ندارند',
                            type: "warning",
                            confirmButtonText: "بستن"
                        });
                        return false;
                    }
                },
                success: function (response) {
                    swal({
                            title: "",
                            text: response,
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "	#5cb85c",
                            cancelButtonText: "خیر ، منصرف شدم",
                            confirmButtonText: "بله ثبت شود",
                            closeOnConfirm: true,
                            closeOnCancel: true
                        },
                        function (isConfirm)
                        {
                            if (isConfirm && response == "مدیر این واحد قبلا انتخاب شده ، آیا در نظر دارید که ایشان را جایگزین مدیر قبلی نمایید؟")
                            {
                                var formData = new FormData($('#user-send-form')[0]);
                                $.ajaxSetup({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                                    }
                                });
                                $.ajax
                                ({
                                    cache : false,
                                    url: "{{ url('admin/newUserCreate') }}/{{1}}",
                                    type: 'post',
                                    //dataType: 'json',
                                    contentType : false,
                                    processData : false,
                                    data: formData,
                                    success: function (response)
                                    {
                                        swal
                                        ({
                                            title: "",
                                            text: response,
                                            type: "info",
                                            confirmButtonText: "بستن"
                                        });
                                        setInterval(function(){ window.location.href= 'usersManagement'; }, 1000);
                                    },error : function(error)
                                    {
                                        if (xhr.status === 422) {
                                            var x = xhr.responseJSON;
                                            var errorsHtml = '';
                                            var count = 0;
                                            $.each(x, function (key, value) {
                                                errorsHtml += value[0] + '\n'; //showing only the first error.
                                            });
                                            console.log(count)
                                            swal({
                                                title: "",
                                                text: errorsHtml,
                                                type: "info",
                                                confirmButtonText: "بستن"
                                            });
                                        }

                                        else if (xhr.status === 500) {
                                            swal({
                                                title: "",
                                                text: "متاسفانه اطلاعات شما ثبت نشد، با پشتیبانی تماس حاصل فرمائید",
                                                type: "warning",
                                                confirmButtonText: "بستن"
                                            });

                                        }
                                    }
                                });

                            }else if(isConfirm && response == "آیا از ثبت کاربر جدید اطمینان دارید؟")
                            {
                                var formData = new FormData($('#user-send-form')[0]);
                                $.ajaxSetup({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                                    }
                                });
                                $.ajax
                                ({
                                    cache : false,
                                    url: "{{ url('admin/newUserCreate') }}/{{2}}",
                                    type: 'post',
                                    //dataType: 'json',
                                    contentType : false,
                                    processData : false,
                                    data: formData,
                                    success: function (response)
                                    {
                                        swal
                                        ({
                                            title: "",
                                            text: response,
                                            type: "info",
                                            confirmButtonText: "بستن"
                                        });
                                        setInterval(function(){ window.location.href= 'usersManagement'; }, 1000);
                                    },error : function(error)
                                    {
                                        if (xhr.status === 422) {
                                            var x = xhr.responseJSON;
                                            var errorsHtml = '';
                                            var count = 0;
                                            $.each(x, function (key, value) {
                                                errorsHtml += value[0] + '\n'; //showing only the first error.
                                            });
                                            console.log(count)
                                            swal({
                                                title: "",
                                                text: errorsHtml,
                                                type: "info",
                                                confirmButtonText: "بستن"
                                            });
                                        }

                                        else if (xhr.status === 500) {
                                            swal({
                                                title: "",
                                                text: "متاسفانه اطلاعات شما ثبت نشد، با پشتیبانی تماس حاصل فرمائید",
                                                type: "warning",
                                                confirmButtonText: "بستن"
                                            });

                                        }
                                    }
                                });
                            }else if(isConfirm && response== 'بنابراینکه واحد مربوطه مدیری ندارد ، مدیر تدارکات بعنوان مدیر این واحد در نظر گرفته میشود.آیا تمایل دارید؟')
                            {
                                var formData = new FormData($('#user-send-form')[0]);
                                $.ajaxSetup({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                                    }
                                });
                                $.ajax
                                ({
                                    cache : false,
                                    url: "{{ url('admin/newUserCreate') }}/{{3}}",
                                    type: 'post',
                                    //dataType: 'json',
                                    contentType : false,
                                    processData : false,
                                    data: formData,
                                    success: function (response)
                                    {
                                        swal
                                        ({
                                            title: "",
                                            text: response,
                                            type: "info",
                                            confirmButtonText: "بستن"
                                        });
                                       setInterval(function(){ window.location.href= 'usersManagement'; }, 1000);
                                    },error : function(error)
                                    {
                                        if (xhr.status === 422) {
                                            var x = xhr.responseJSON;
                                            var errorsHtml = '';
                                            var count = 0;
                                            $.each(x, function (key, value) {
                                                errorsHtml += value[0] + '\n'; //showing only the first error.
                                            });
                                            console.log(count)
                                            swal({
                                                title: "",
                                                text: errorsHtml,
                                                type: "info",
                                                confirmButtonText: "بستن"
                                            });
                                        }

                                        else if (xhr.status === 500) {
                                            swal({
                                                title: "",
                                                text: "متاسفانه اطلاعات شما ثبت نشد، با پشتیبانی تماس حاصل فرمائید",
                                                type: "warning",
                                                confirmButtonText: "بستن"
                                            });

                                        }
                                    }
                                });
                            }else if(isConfirm && response== 'آیا از ثبت این کاربر به عنوان مدیر واحد انتخاب شده اطمینان دارید؟')
                            {
                                var formData = new FormData($('#user-send-form')[0]);
                                $.ajaxSetup({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                                    }
                                });
                                $.ajax
                                ({
                                    cache : false,
                                    url: "{{ url('admin/newUserCreate') }}/{{4}}",
                                    type: 'post',
                                    //dataType: 'json',
                                    contentType : false,
                                    processData : false,
                                    data: formData,
                                    success: function (response)
                                    {
                                        swal
                                        ({
                                            title: "",
                                            text: response,
                                            type: "info",
                                            confirmButtonText: "بستن"
                                        });
                                        setInterval(function(){ window.location.href= 'usersManagement'; }, 1000);
                                    },error : function(error)
                                    {
                                        if (xhr.status === 422) {
                                            var x = xhr.responseJSON;
                                            var errorsHtml = '';
                                            var count = 0;
                                            $.each(x, function (key, value) {
                                                errorsHtml += value[0] + '\n'; //showing only the first error.
                                            });
                                            console.log(count)
                                            swal({
                                                title: "",
                                                text: errorsHtml,
                                                type: "info",
                                                confirmButtonText: "بستن"
                                            });
                                        }

                                        else if (xhr.status === 500) {
                                            swal({
                                                title: "",
                                                text: "متاسفانه اطلاعات شما ثبت نشد، با پشتیبانی تماس حاصل فرمائید",
                                                type: "warning",
                                                confirmButtonText: "بستن"
                                            });

                                        }
                                    }
                                });
                            }
                        }
                    )
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        var x = xhr.responseJSON;
                        var errorsHtml = '';
                        var count = 0;
                        $.each(x, function (key, value) {
                            errorsHtml += value[0] + '\n'; //showing only the first error.
                        });
                        console.log(count)
                        swal({
                            title: "",
                            text: errorsHtml,
                            type: "info",
                            confirmButtonText: "بستن"
                        });
                    }

                    else if (xhr.status === 500) {
                        swal({
                            title: "",
                            text: "متاسفانه اطلاعات شما ثبت نشد، با پشتیبانی تماس حاصل فرمائید",
                            type: "warning",
                            confirmButtonText: "بستن"
                        });

                    }
                }
            });

        });
    </script>
    {{-- load user's supervisor opon unit_id --}}
    <script>
        $.ajaxSetup({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        });
        $("#unit_id").on("change", function () {
            var uid = $(this).val();
            var token = $(this).data("token");
            $.ajax({
                url: '{{url('admin/usersSupervisor')}}',
                type: 'post',
                dataType: "JSON",
                data: {
                    "id": uid,
                    "_token": token
                },
                success: function (data) {
                    var item = $('#supervisor_id');
                    item.empty();
                    console.log(data);
                    $.each(data, function (index, value) {
                        item.append('<option name="supervisor" value="' + value.id + '">' + value.name + ' ' + value.family + '</option>');
                    });

                },
                error: function (response) {
                    console.log(response.valueOf(2));
                }
            });
        });
    </script>

        <script>
            $(function () {
                $(':checkbox').change(function() {

                    if($(this).val() == 1)
                    {
                        $(this).val(0);
                    }
                    else
                        {
                           $(this).val(1);
                        }
                });
            })

        </script>
@endsection
