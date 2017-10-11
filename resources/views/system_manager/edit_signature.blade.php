@extends('layouts.adminLayout')
@section('content')
        <!-- page content -->
<div class="" role="main">
    <div class="">
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-8 col-sm-12 col-xs-12 col-md-offset-2">
                <div class="x_panel">
                    <div class="x_title">
                        <h2></h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link" data-toggle="tooltip" title="جمع کردن"><i
                                            class="fa fa-chevron-up"></i></a>
                            </li>
                            <li><a class="close-link" data-toggle="tooltip" title="بستن"><i class="fa fa-close"></i></a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <br/>
                        <form class="form-horizontal form-label-right input_mask" id="dealForm" style="direction: rtl;">
                            {!! csrf_field() !!}
                            <input type="hidden" name="dealTypeName" id="dealTypeName" value="">
                            <input type="hidden" name="dealTypeEn" id="dealTypeEn" value="">
                            <input type="hidden" name="property_id" id="property_id" value="">
                            <div class="row">
                                <div class="col-md-12" style="">
                                    <p style="color:red;font-size: 18px;">چنانچه صاحب امضاء را بعنوان کاربر تعریف نکرده اید ابتدا  : <a href="" ><i class="fa fa-user"></i> کاربر جدید را ثبت کنید</a></p>
                                </div>
                            </div>

                            <label style="font-size: 20px;margin-bottom: 10px;"
                                   class="control-label pull-right col-md-12 col-sm-12 col-xs-12 form-group"> مشخصات
                                مالک امضاء
                            </label>
                            {{--<div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback pull-right">--}}
                            <div class="col-md-6 col-sm-6 col-xs-12 form-group pull-right">
                                <select class="form-control" id="unit_signature" name="unit_signature">
                                    <option>انتخاب واحد</option>
                                    @foreach($units as $unit)
                                        <option value="{{$unit->id}}" @if($unit->id==$signature_info[0]->unit_id) selected @endif>{{$unit->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                            {{--</div>--}}
                            <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                                <select class="form-control" id="user_signature" name="user_signature">
                                </select>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback pull-right">
                                <input type="number" class="form-control" style="text-align:right;" id="signature_priority"
                                       name="signature_priority" placeholder="درجه اهمیت" min="1" max="5">
                                <span class="fa fa-info-circle form-control-feedback right" aria-hidden="true"></span>
                            </div>
                            <div class="row">
                                <div class="input-group image-preview col-md-12">
                                    <input type="text" class="form-control image-preview-filename" disabled="disabled">
                                    <!-- don't give a name === doesn't send on POST/GET -->
                                    <span class="input-group-btn">
                                    <!-- image-preview-clear button -->
                                    <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
                                        <span class="glyphicon glyphicon-remove"></span> پاک کردن
                                    </button>
                                        <!-- image-preview-input -->
                                    <div class="btn btn-default image-preview-input ">
                                        <span class="glyphicon glyphicon-folder-open"></span>
                                        <span class="image-preview-input-title">انتخاب تصویر امضاء</span>
                                        <input type="file" accept="image/png, image/jpeg, image/gif" name="input-file-preview"/>
                                        <!-- rename it -->
                                    </div>
                                    </span>
                                </div><!-- /input-group image-preview [TO HERE]-->
                            </div>
                            <div class="ln_solid"></div>
                            <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-3 col-sm-offset-3 col-xs-offset-1">
                                {{--<button type="submit" name="edit" id="edit" class="btn btn-primary col-md-3 col-sm-3 col-xs-5">ویرایش</button>--}}
                                <button type="button" name="add_signature" id="add_signature"
                                        class="btn btn-success col-md-6 col-sm-3 col-xs-5">ثبت امضاء
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection