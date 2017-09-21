@extends('layouts.adminLayout1')
@section('content')
        <!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-9 col-sm-12 col-xs-12 ">
                <div class="x_panel">
                    <div class="x_title">
                        <h2></h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link" data-toggle="tooltip" title="جمع کردن"><i class="fa fa-chevron-up"></i></a>
                            </li>
                            <li><a class="close-link" data-toggle="tooltip" title="بستن"><i class="fa fa-close"></i></a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <br />
                        <form class="form-horizontal form-label-right input_mask" id="dealForm" style="direction: rtl;">
                            {!! csrf_field() !!}
                            <input type="hidden" name="dealTypeName" id="dealTypeName" value="">
                            <input type="hidden" name="dealTypeEn" id="dealTypeEn" value="">
                            <input type="hidden" name="property_id" id="property_id" value="">

                            <label style="font-size: 20px;margin-bottom: 10px;" class="control-label pull-right col-md-12 col-sm-12 col-xs-12 form-group"> مشخصات مالک امضاء
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback pull-right">
                                <input type="text" class="form-control" style="text-align:right;" id="owner" name="owner" placeholder="نام">
                                <span class="fa fa-user form-control-feedback right" aria-hidden="true"></span>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
                                <input type="text" class="form-control" style="text-align:right;" id="buyer" name="buyer" placeholder="نام خانوادگی">
                                <span class="fa fa-user form-control-feedback right" aria-hidden="true"></span>
                            </div>
                            {{--<div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback pull-right">--}}
                                <div class="col-md-6 col-sm-6 col-xs-12 pull-right">
                                    <select class="form-control" id="unit_signature" name="unit_signature">
                                        <option readonly>انتخاب واحد</option>
                                        @foreach($units as $unit)
                                        <option value="{{$unit->id}}">{{$unit->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            {{--</div>--}}
                            <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                                <select class="form-control" id="user_signature" name="user_signature">
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12 pull-right">
                                    <label style="font-size: 20px;margin-bottom: 10px;" class="control-label pull-right col-md-12 col-sm-12 col-xs-12 form-group"> توضیحات قرار داد :
                                    </label>
                                    <textarea class="col-md-12 col-sm-12 col-xs-12" name="info" id="info" rows="5" style="resize: vertical;"></textarea>
                                </div>
                            </div>
                            <div class="ln_solid"></div>
                            <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-3 col-sm-offset-3 col-xs-offset-1">
                                {{--<button type="submit" name="edit" id="edit" class="btn btn-primary col-md-3 col-sm-3 col-xs-5">ویرایش</button>--}}
                                <button type="button" name="addDeal" id="addDeal" class="btn btn-success col-md-6 col-sm-3 col-xs-5">ثبت معامله</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection