@extends('layouts.adminLayout')
@section('content')
<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-8 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2> تغییر رمز عبور کاربر</h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link" data-toggle="tooltip" title="جمع کردن"><i class="fa fa-chevron-up"></i></a>
                            </li>
                            <li><a class="close-link" data-toggle="tooltip" title="بستن"  ><i class="fa fa-close"></i></a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <br />
                        {{--<a href="{{url('admin/realStateManagement')}}" class="btn btn-warning col-md-6 col-xs-12 col-sm-12 col-md-offset-3" style="margin-bottom: 20px;display: none;" id="goAdminPage">بازگشت به مدیریت</a>--}}
                        <form method="POST" action="{{ url('admin/saveNewPassword') }}" class="form-horizontal form-label-left input_mask" style="direction:rtl" >
                            {{ csrf_field() }}
                            <input type="hidden" value="{{$userInfo[0]->id}}" name="userId" id="userId">
                            <div class="form-group">
                                <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
                                    <input type="text" class="form-control" id="name" readonly value="{{$userInfo[0]->name}}">
                                    <span class="fa fa-user form-control-feedback right" aria-hidden="true"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
                                    <input type="text" class="form-control" id="family" readonly value="{{$userInfo[0]->family}}">
                                    <span class="fa fa-user form-control-feedback right" aria-hidden="true"></span>
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
                                    <input id="password" type="password" name="password"  class="form-control" placeholder="رمز عبور جدید" >
                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                           <strong>{{ $errors->first('password') }}</strong>
                                           </span>
                                    @endif
                                    <span class="fa fa-lock form-control-feedback right" aria-hidden="true"></span>
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
                                    <input id="password-confirm" type="password" name="password_confirmation"  class="form-control" placeholder="تکرار رمز عبور" >
                                    @if ($errors->has('password_confirmation'))
                                        <span class="help-block">
                                             <strong>{{ $errors->first('password_confirmation') }}</strong>
                                             </span>
                                    @endif
                                    <span class="fa fa-lock form-control-feedback right" aria-hidden="true"></span>
                                </div>
                            </div>

                            <div class="ln_solid"></div>
                            <div class="form-group">
                                <div class="col-md-5 col-sm-9 col-xs-12 col-md-offset-3 col-sm-offset-2">
                                    {{--<button type="submit" id="saveChangePassButton" class="btn btn-success col-md-12 col-sm-12 col-xs-12" style="font-size:20px;">ذخیره رمز عبور جدید</button>--}}
                                    <button type="button" id="saveChangePassButton" class="btn btn-success col-md-12 col-sm-12 col-xs-12" style="font-size:20px;">ذخیره رمز عبور جدید</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
