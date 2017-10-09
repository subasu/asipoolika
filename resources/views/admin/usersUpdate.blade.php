@extends('layouts.adminLayout')
@section('content')
    <style>
        .star {
            color: #ff0000;float: right;
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
                    <h2> فرم ویرایش کاربر
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
                        @foreach($user as $val)
                            <form class="form-horizontal form-label-left" id="user-send-form" method="POST"
                                  style="direction: rtl !important;">
                                {{ csrf_field() }}
                                <input value="{{$val->id}}" name="user_id" type="hidden">
                                <div class="item form-group">
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <input id="title" class="form-control col-md-7 col-xs-12" name="title"
                                               value="{{$val->title}}"
                                               required="required" type="text">
                                    </div>
                                    <label class="control-label col-md-4 col-sm-4 col-xs-12 " for="title"> عنوان <span
                                                class="required star" title="پر کردن این فیلد الزامی است" >*</span>
                                    </label>
                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <input value="{{$val->id}}" name="unit_id" type="hidden">
                                <div class="item form-group">
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <input id="name" class="form-control col-md-7 col-xs-12" name="name"
                                               value="{{$val->name}}"
                                               required="required " type="text">
                                    </div>
                                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="name"> نام <span
                                                class="required star" title="پر کردن این فیلد الزامی است" >*</span>
                                    </label>
                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="item form-group {{ $errors->has('family') ? ' has-error' : '' }}">
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <input id="family" class="form-control col-md-7 col-xs-12" name="family"
                                               value="{{$val->family}}" required="required" type="text">
                                    </div>
                                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="family"> نام خانوادگی
                                        <span class="required star" title="پر کردن این فیلد الزامی است">*</span>
                                    </label>
                                    @if ($errors->has('family'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('family') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="item form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <input type="email" id="email" name="email" required="required"
                                               value="{{$val->email}}" class="form-control col-md-7 col-xs-12">
                                    </div>
                                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="email"> ایمیل <span
                                                class="required star" title="پر کردن این فیلد الزامی است">*</span>
                                    </label>
                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="item form-group {{ $errors->has('cellphone') ? ' has-error' : '' }}">
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <input type="tel" id="cellphone" name="cellphone" required="required"
                                               value="{{$val->cellphone}}" class="form-control col-md-7 col-xs-12">
                                    </div>
                                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="cellphone">شماره
                                        موبایل
                                        <span class="required star" title="پر کردن این فیلد الزامی است">*</span></label>
                                    @if ($errors->has('cellphone'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('cellphone') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="item form-group {{ $errors->has('internal_phone') ? ' has-error' : '' }}">
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <input type="tel" id="internal_phone" name="internal_phone"
                                               value="{{$val->internal_phone}}" required="required"
                                               class="form-control col-md-7 col-xs-12">
                                    </div>
                                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="internal_phone">تلفن
                                        داخلی
                                        <span class="required star" title="پر کردن این فیلد الزامی است">*</span></label>
                                    @if ($errors->has('internal_phone'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('internal_phone') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                {{--<div class="item form-group">--}}
                                    {{--<div class="col-md-8 col-sm-8 col-xs-12">--}}
                                        {{--<select class="form-control col-md-7 col-xs-12" name="unit_id" id="unit_id">--}}
                                            {{--<option value="{{$val->unit_id}}">{{$val->unit->title}}</option>--}}
                                            {{--@foreach($units as $unit)--}}
                                                {{--<option class="align-right"--}}
                                                        {{--value="{{$unit->id}}">{{$unit->title}}</option>--}}
                                            {{--@endforeach--}}
                                        {{--</select>--}}
                                    {{--</div>--}}
                                    {{--<label class="control-label col-md-4 col-sm-4 col-xs-12" for="unit_id"> واحد--}}
                                        {{--<span class="required" title="پر کردن این فیلد الزامی است">*</span></label>--}}
                                {{--</div>--}}

                                {{--<div class="item form-group">--}}
                                    {{--<div class="col-md-8 col-sm-8 col-xs-12">--}}
                                        {{--<select class="form-control col-md-7 col-xs-12" name="supervisor_id"--}}
                                                {{--id="supervisor_id">--}}
                                            {{--<option value="{{$val->supervisor_id}}">{{$val->user->title. ' '.$val->user->name.' '.$val->user->family}}</option>--}}
                                        {{--</select>--}}
                                    {{--</div>--}}
                                    {{--<label class="control-label col-md-4 col-sm-4 col-xs-12" for="supervisor_id">سرپرست--}}
                                        {{--<span class="required" title="پر کردن این فیلد الزامی است">*</span></label>--}}
                                {{--</div>--}}
                                <div class="item form-group">
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                    <textarea id="description" required="required" name="description"
                                              class="form-control col-md-7 col-xs-12">{{$val->description}}</textarea>
                                    </div>
                                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="description">توضیحات
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
                                        <button id="user-send" type="button" class="col-md-12 btn btn-primary">ثبت
                                        </button>
                                    </div>
                                </div>
                            </form>
                        @endforeach
                    </div>
                </div>
                <div class="col-md-4 col-sm-3 col-xs-12"></div>
            </div>
        </div>

    {{--update user's information by ajax--}}
    <script>
        $("#user-send").click(function () {
            var formData = new FormData($('#user-send-form')[0]);
            $.ajax({
                type: 'post',
                cache: false,
                url: "{{Url('admin/usersUpdate')}}",
                data: formData,
                dataType: 'json',
                contentType: false,//very important for upload file
                processData: false,//very important for upload file
                success: function (data) {
                    swal({
                        title: "",
                        text: "اطلاعات شما با مؤفقیت ویرایش شد",
                        type: "info",
                        confirmButtonText: "بستن"
                    });
                        setInterval(function () {
                            window.location.href ='../usersManagement';
                        },1000);


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
                            text: "متاسفانه اطلاعات شما ثبت نشد،با پشتیبانی تماس حاصل فرمائید",
                            type: "warning",
                            confirmButtonText: "بستن"
                        });

                    }
                }
            });

        });

    </script>
    {{--edit user's info by user-id --}}
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
                        item.append('<option value="' + value.id + '">' + value.name + ' ' + value.family + '</option>');
                    });

                },
                error: function (response) {
                    console.log(response.valueOf(2));
                }
            });
        });
    </script>
@endsection
