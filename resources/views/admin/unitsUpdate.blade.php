@extends('layouts.adminLayout');
@section('title')
    ویرایش واحد
@endsection
@section('content')
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2> فرم ویرایش واحد
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
                <div class="col-md-6 col-sm-6 col-xs-12" dir="rtl">
                    <div class="x_content" dir="rtl">
                        @foreach($unit as $val)
                            <form style="text-align: right !important;" class="form-horizontal form-label-left" id="unit-send-form" method="POST" action="{{url('')}}">
                                {{ csrf_field() }}
                                <input value="{{$val->id}}" name="unit_id" type="hidden">
                                <div class="item form-group" {{ $errors->has('title') ? ' has-error' : '' }}>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input id="title" class="form-control col-md-7 col-xs-12" name="title"
                                               placeholder="" value="{{$val->title}}"
                                               required="required" type="text">
                                    </div>
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title"> عنوان <span
                                                class="required" title="پر کردن این فیلد الزامی است">*</span>
                                    </label>
                                    @if ($errors->has('title'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="item form-group" {{ $errors->has('phone') ? ' has-error' : '' }}>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="phone" id="tel" name="phone" required="required"
                                               data-validate-length-range="8,20" value="{{$val->phone}}"
                                               class="form-control col-md-7 col-xs-12">
                                    </div>
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="phone">شماره داخلی
                                    </label>
                                    @if ($errors->has('phone'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="item form-group">
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                    <textarea id="description" required="required" name="description"
                                              class="form-control col-md-7 col-xs-12">{{$val->description}}</textarea>
                                    </div>
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">توضیحات
                                    </label>
                                </div>
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 ">
                                        <button id="unit-send" type="button" class="col-md-3 btn btn-primary">ویرایش
                                        </button>
                                    </div>
                                </div>
                            </form>
                        @endforeach
                    </div>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-12"></div>
            </div>
        </div>
    </div>
    {{--edit unit's info by unit-id --}}
    <script>
        $("#unit-send").click(function () {
            var formData = new FormData($('#unit-send-form')[0]);
            $.ajax({
                type: 'post',
                cache: false,
                url: "{{URL::asset('admin/unitsUpdate')}}",
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
                        top.location = '{{URL::asset('admin/unitsManage')}}';
                    }, 2000);
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
                    else if (xhr.status === 421) {
                        swal({
                            title: "",
                            text: "اطلاعات شما با مؤفقیت ثبت شد",
                            type: "info",
                            confirmButtonText: "بستن"
                        });
                        setInterval(function () {
                            top.location = '{{URL::asset('admin/unitsManage')}}';
                        }, 3000);
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
@endsection
