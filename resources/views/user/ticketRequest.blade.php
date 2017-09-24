@extends('layouts.adminLayout');
@section('content')
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
            <div class="x_panel">
                <div class="x_title">
                    <h2>  ارسال تیکت
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

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_content">
                        <form class="form-horizontal form-label-left" id="unit-send-form" method="POST">
                            {{ csrf_field() }}
                            <div class="item form-group">
                                <div class="col-md-9 col-sm-6 col-xs-12">
                                    <select class="form-control col-md-7 col-xs-12" style="direction: rtl;" name="unit_id" id="unit_id">
                                        <option value="" class="text-right">واحد مربوطه را انتخاب نمایید</option>
                                        {{--@foreach($units as $unit)--}}
                                            {{--<option class="align-right" value="{{$unit->id}}">{{$unit->title}}</option>--}}
                                        {{--@endforeach--}}
                                    </select>
                                </div>
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="unit_id"> واحد
                                    <span class="required" title="پر کردن این فیلد الزامی است">*</span></label>
                            </div>
                            <div class="item form-group" {{ $errors->has('title') ? ' has-error' : '' }}>
                                <div class="col-md-9 col-sm-6 col-xs-12">
                                    <input id="title" class="form-control col-md-7 col-xs-12" name="title"
                                           placeholder=""
                                           required="required" type="text">
                                </div>
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">عنوان تیکت <span
                                            class="required" title="پر کردن این فیلد الزامی است"
                                            style="color:red">*</span>
                                </label>
                                @if ($errors->has('title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="item form-group">
                                <div class="col-md-9 col-sm-6 col-xs-12">
                                    <textarea id="description" required="required" name="description"
                                              class="form-control col-md-7 col-xs-12"></textarea>
                                </div>
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">متن تیکت
                                    <span class="required" title="پر کردن این فیلد الزامی است"
                                            style="color:red">*</span>
                                </label>
                            </div>
                            <div class="ln_solid"></div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <button id="unit-send" type="button" class="col-md-9 btn btn-primary">ثبت</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function(){
                //alert('hello');
                $.ajax
                ({
                    url  : "{{Url('user/getUnits')}}",
                    type : "get",
                    dataType : "JSON",
                    success:function()
                    {

                    },
                    error:function(error)
                    {
                        alert('error');
                        console.log(error);

                    }
                })
            });
        </script>
@endsection