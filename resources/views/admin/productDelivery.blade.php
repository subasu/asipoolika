@extends('layouts.panelLayout');
@section('title')
    صورت جلسه تحویل کالا و مصرف
@endsection
@section('content')
    <div class="page-title">
        <div class="title_right">
            <h3>
                صورت جلسه تحویل کالا و مصرف
            </h3>
        </div>
        {{--<div class="title_left">--}}
            {{--<div class="col-md-5 col-sm-5 col-xs-12 form-group pull-left top_search">--}}
                {{--<div class="input-group">--}}
                    {{--<input type="text" class="form-control text-center" dir="rtl" placeholder="جستجو">--}}
                    {{--<span class="input-group-btn">--}}
                            {{--<button class="btn btn-default" type="button">بگرد</button>--}}
                        {{--</span>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    </div>
    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2></h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>

                <div class="col-md-2 col-sm-2 col-xs-12"></div>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <div class="x_content">
                        <form id="" name="">
                            <h3 class="text-center">
                                دانشگاه علوم پزشکی و خدمات بهداشتی و درمانی استان اصفهان
                            </h3><br>
                        <table class="table table-bordered" dir="rtl">
                            <tr>
                                <th class="remove-border col-md-4 text-right"> فرم شماره 9</th>
                                <th class="remove-border col-md-3 text-center">نام واحد
: شبکه بهداشت و درمان شهرستان خمینی شهر                                <th class="remove-border col-md-4 text-left">شماره ثبت
  &nbsp;<input id="name" name="name" class="col-md-5" required="required" type="text">
                                </th>
                            </tr>
                        </table>
                        <br>
                        <h3 class="text-center">« صورت جلسه تحویل کالا و مصرف »</h3><br>
                        <h4 dir="rtl" class="text-justify">بدینوسیله گواهی می شود خدمات انجام شده به شرح زیر توسط شرکت/
                            فروشگاه
                            <input id="name" name="name" required="required" type="text">
                            جهت واحد
                            <input id="name" name="name" required="required" type="text">
                            به آقای/خانم
                            <input id="name" name="name" required="required" type="text">
                            تحویل گردید و پرداخت بلامانع است.</h4>
                        <br>
                        <table class="table table-bordered mytable" dir="rtl">
                            <thead>
                            <tr>
                                <th class="col-md-1">ردیف</th>
                                <th class="col-md-2">شرح</th>
                                <th class="col-md-2">تعداد</th>
                                <th class="col-md-2"> مبلغ کل (ریال)</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <th class="col-md-1">1</th>
                                <td class="col-md-2">222</td>
                                <td class="col-md-2">333</td>
                                <td class="col-md-2">عدد</td>
                            </tr>
                            <tr>
                                <td class="col-md-1">جمع کل به حروف</td>
                                <td class="col-md-2">
                                    <input id="name" class="form-control" name="name" required="required" type="text">
                                <th> جمع کل</th>
                                <th> 100000</th>
                            </tr>
                            </tbody>
                        </table>
                        <table class="table table-bordered mytable" dir="rtl">
                            <tr>
                                <th class="remove-border col-md-3">دریافت کننده خدمات
                                    <input id="name" name="name"class="" required="required" type="text">
                                </th>
                                <th class="remove-border col-md-3">مسئول واحد
                                    <input id="name" name="name"class="" required="required" type="text">
                                </th>
                                <th class="remove-border col-md-3">کارپرداز
                                    <input id="name" name="name"class="" required="required" type="text">
                                <th class="remove-border col-md-3">رئیس واحد
                                    &nbsp;<input id="name" name="name" class="" required="required" type="text">
                                </th>
                            </tr>
                        </table>
                            <hr>
                            <div class="form-group">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <button id="send" type="submit" class="btn btn-primary col-md-2 col-lg-offset-4">ثبت</button>
                                        <button id="send" type="submit" class="btn btn-info col-md-2">چاپ</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                {{--! end tables --}}
            </div>
        </div>
    </div>
@endsection
