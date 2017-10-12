@extends('layouts.adminLayout')
@section('content')
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-7 col-sm-12 col-xs-12 col-md-offset-2">
            <div class="x_panel">
                <div class="x_title">
                    <h2> تعیین سطح دسترسی
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

                <div class="col-md-12 col-sm-6 col-xs-12">
                    <div class="x_content">
                        <form class="form-horizontal form-label-left" id="unit-send-form" method="POST" style="text-align: right;direction: rtl;font-size: 15px;">
                            {{ csrf_field() }}
                            <div class="item form-group" {{ $errors->has('title') ? ' has-error' : '' }}>
                                <label class="control-label col-md-4 col-sm-3 col-xs-12 pull-right" for="title"><i class="fa fa-user"></i> کاربر :

                                </label>
                                <div class="col-md-8 col-sm-6 col-xs-12">
                                    <input id="title" class="form-control col-md-7 col-xs-12" name="title"
                                           placeholder="" value="{{$userFullName}}"
                                           required="required" type="text">
                                </div>
                                @if ($errors->has('title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="item form-group" {{ $errors->has('phone') ? ' has-error' : '' }}>
                                <label class="control-label col-md-4 col-sm-3 col-xs-12  pull-right" for="phone"><i class="fa fa-align-right"></i> سطح دسترسی های فعلی :
                                </label>
                                <div class="col-md-8 col-sm-6 col-xs-12">
                                    <label class="control-label" for="phone"><i class="fa fa-align-right"></i> سطح دسترسی های فعلی :
                                        {{$userRoles}}
                                    </label>
                                </div>
                            </div>
                            <div class="item form-group" {{ $errors->has('phone') ? ' has-error' : '' }}>
                                <label class="control-label col-md-4 col-sm-3 col-xs-12  pull-right" for="phone"><i class="fa fa-plus"></i> افزودن سطح دسترسی :
                                </label>
                                <div class="col-md-8 col-sm-6 col-xs-12">
                                    <select class="form-control" id="level" name="level">
                                        @foreach($roles as $role)
                                            <option value="{{$role->id}}">{{$userRoles,0,-1}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="ln_solid"></div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <button id="newRole" type="button" class="col-md-9 btn btn-primary">ثبت</button>
                                </div>
                            </div>
                            <input type="hidden" value="{{$id}}" name="userId">
                        </form>
                    </div>
                </div>

            </div>
        </div>

        <script>
            $("#newRole").click(function () {
                var formData = new FormData($('#unit-send-form')[0]);
                $.ajax({
                    type: 'post',
                    cache: false,
                    url: "{{URL::asset('systemManager/newRole')}}",
                    data: formData,
                   // dataType: 'json',
                    contentType: false,//very important for upload file
                    processData: false,//very important for upload file
                    success: function (data) {
                        swal({
                            title: "",
                            text: data,
                            type: "info",
                            confirmButtonText: "بستن"
                        });
                        setInterval(function () {
                            window.location.reload();
                        }, 3000);
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
                        else if (xhr.status === 500){
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
