@extends('layouts.adminLayout');
@section('content')
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-8 col-sm-8 col-xs-12 col-md-offset-2">
            <div class="x_panel">
                <div class="x_title">
                    <h2>  محتوای تیکت
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
                <div class="col-md-12 col-sm-12 col-xs-12 ">
                    <div class="x_content" dir="rtl">
                            <div class="item form-group">
                                <div class="col-md-9 col-sm-7 col-xs-12">
                                    <input id="title" class="form-control col-md-7 col-xs-10" name="title"
                                           disabled type="text" style="margin-bottom: 1.5%;">
                                </div>
                                <label class="control-label col-md-3 col-sm-1 col-xs-2" for="unit_id"> واحد</label>
                        </div>
                            <div class="item form-group">
                                <div class="col-md-9 col-sm-8 col-xs-12">
                                    <input id="title" class="form-control col-md-7 col-xs-10" name="title"
                                          disabled type="text" style="margin-bottom: 1.5%;">
                                </div>
                                <label class="control-label col-md-3 col-sm-1 col-xs-2 " for="title">عنوان تیکت</label>
                            </div>
                            <div class="item form-group">
                                <div class="col-md-9 col-sm-8 col-xs-12">
                                     <textarea id="description"  name="description" disabled
                                               class="form-control col-md-7 col-xs-10"></textarea>
                                </div>
                                <label class="control-label col-md-3 col-sm-1 col-xs-2" for="description">متن تیکت</label>
                            </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8 col-sm-8 col-xs-12 col-md-offset-2">
            <div class="x_panel">
                <div class="x_title">
                    <h2>   گفتگــو ها
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
                <div class="col-md-12 col-sm-12 col-xs-12 ">
                    <div class="x_content ">{{-- AdminConversation--}}
                            <div class="item form-group" dir="rtl">
                                <div class="col-md-9 col-sm-6 col-xs-12">
                                    <textarea id="description" required="required" name="description"
                                              class="form-control col-md-7 col-xs-12" disabled></textarea>
                                </div>
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">مسئول مربوطه
                                </label>
                            </div>
                    </div>
                    <div class="x_content">{{-- UserConversation--}}
                    <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">کاربر
                            </label>
                                <div class="col-md-9 col-sm-6 col-xs-12">
                                    <textarea id="description" required="required" name="description"
                                              class="form-control col-md-7 col-xs-12" disabled></textarea>
                                </div>
                            </div>
                    </div>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_content">
                        <div class="ln_solid"></div>
                        <form class="form-horizontal form-label-left" id="unit-send-form" method="POST">
                            {{ csrf_field() }}
                            <div class="item form-group">
                                <div class="col-md-9 col-sm-6 col-xs-12">
                                    <textarea id="description" required="required" name="description"
                                              class="form-control col-md-7 col-xs-12"></textarea>
                                </div>
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">پاسخ شما
                                </label>
                            </div>
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
@endsection