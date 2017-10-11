@extends('layouts.panelLayout')
@section('content')
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2> فرم ایجاد واحد
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
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="x_content">
                        <form class="form-horizontal form-label-left" novalidate>
                            <div class="item form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input id="title" class="form-control col-md-7 col-xs-12" name="title" placeholder=""
                                           required="required" type="text">
                                </div>
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title"> عنوان <span class="required" title="پر کردن این فیلد الزامی است">*</span>
                                </label>
                            </div>
                            <div class="item form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="phone" id="tel" name="phone" required="required"
                                           data-validate-length-range="8,20" class="form-control col-md-7 col-xs-12">
                                </div>
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="phone">شماره داخلی
                                </label>
                            </div>
                            <div class="item form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <textarea id="description" required="required" name="description" class="form-control col-md-7 col-xs-12"></textarea>
                                </div>
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">توضیحات
                                </label>
                            </div>
                            <div class="ln_solid"></div>
                            <div class="form-group">
                                <div class="col-md-6 ">
                                    <button id="send" type="submit" class="col-md-3 btn btn-primary">ثبت</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-12"></div>
            </div>
        </div>
    </div>
@endsection
