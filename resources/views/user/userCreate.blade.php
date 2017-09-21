@extends('layouts.panelLayout');
@section('title')
    ایجاد کاربر
@endsection
@section('content')
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
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
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="x_content">
                        <form class="form-horizontal form-label-left" novalidate>
                            <div class="item form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input id="name" class="form-control col-md-7 col-xs-12" name="name" placeholder=""
                                           required="required" type="text">
                                </div>
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"> نام <span class="required" title="پر کردن این فیلد الزامی است">*</span>
                                </label>
                            </div>
                            <div class="item form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input id="family" class="form-control col-md-7 col-xs-12" name="family"
                                           placeholder="" required="required" type="text">
                                </div>
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="family"> نام خانوادگی <span class="required" title="پر کردن این فیلد الزامی است">*</span>
                                </label>
                            </div>
                            <div class="item form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="email" id="email" name="email" required="required"
                                           class="form-control col-md-7 col-xs-12">
                                </div>
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email"> ایمیل <span class="required" title="پر کردن این فیلد الزامی است">*</span>
                                </label>
                            </div>
                            <div class="item form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input id="password" type="password" name="password" data-validate-length="6,8"
                                           class="form-control col-md-7 col-xs-12" required="required">
                                </div>
                                <label for="password" class="control-label col-md-3"> رمز عبور <span class="required" title="پر کردن این فیلد الزامی است">*</span></label>
                            </div>
                            <div class="item form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input id="password2" type="password" name="password2"
                                           data-validate-linked="password" class="form-control col-md-7 col-xs-12"
                                           required="required">
                                </div>
                                <label for="password2" class="control-label col-md-3 col-sm-3 col-xs-12">  تکرار رمز عبور <span class="required" title="پر کردن این فیلد الزامی است">*</span></label>
                            </div>
                            <div class="item form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="tel" id="telephone" name="" required="required"
                                           data-validate-length-range="8,20" class="form-control col-md-7 col-xs-12">
                                </div>
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="telephone">شماره موبایل
                                </label>
                            </div>
                            <div class="item form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="tel" id="	internal_phone" name="	internal_phone" required="required"
                                           data-validate-length-range="8,20" class="form-control col-md-7 col-xs-12">
                                </div>
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="	internal_phone">تلفن داخلی
                                </label>
                            </div>
                            <div class="item form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="form-control col-md-7 col-xs-12" name="unit_id">
                                        <option value="">1</option>
                                        <option value="">2</option>
                                    </select>
                                </div>
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="unit_id">شماره واحد
                                </label>
                            </div>
                            <div class="item form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="form-control col-md-7 col-xs-12" name="supervisor_id">
                                        <option value="">1</option>
                                        <option value="">2</option>
                                    </select>
                                </div>
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="supervisor_id">سرپرست
                                </label>
                            </div>
                            <div class="item form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <textarea id="description" required="required" name="description"
                                              class="form-control col-md-7 col-xs-12"></textarea>
                                </div>
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">توضیحات
                                </label>
                            </div>
                            <div class="ln_solid"></div>
                            <div class="form-group">
                                <div class="col-md-6">
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
