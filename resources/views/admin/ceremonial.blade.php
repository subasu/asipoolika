@extends('layouts.panelLayout');
@section('title')
    فرم تشریفات
@endsection
@section('content')
    <div class="page-title">
        <div class="title_right">
            <h3>
                فرم تشریفات
            </h3>
        </div>
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
                            <h2 class="text-center">بسمه تعالی</h2>
                            <h4 class="text-center">
                                دانشگاه علوم پزشکی و خدمات بهداشتی و درمانی استان اصفهان
                            </h4>
                            <h4 class="text-center">صورت انجام هزینه های بند (و) 45 آیین نامه مالی و معاملاتی دانشگاه مصوب مرداد ماه سال 90</h4><br>
                            <br>
                            <table class="table table-bordered mytable" dir="rtl">
                                <thead>
                                <tr>
                                    <th class="col-md-1">ردیف</th>
                                    <th class="col-md-2">شرح هزینه</th>
                                    <th class="col-md-2"> جمع مبلغ (ریال)</th>
                                    <th class="col-md-2">تاریخ مصرف </th>
                                    <th class="col-md-2">موارد مصرف </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <th class="col-md-1">1</th>
                                    <td class="col-md-2">222</td>
                                    <td class="col-md-2">333</td>
                                    <td class="col-md-2"> </td>
                                    <td class="col-md-2"> </td>
                                </tr>
                                <tr>
                                    <th> جمع کل</th>
                                    <th colspan="4">
                                        <input id="name" class="form-control" name="name" required="required" type="text">
                                    </th>
                                </tr>
                                </tbody>
                            </table>
                            <table class="table table-bordered mytable" dir="rtl">
                                <tr>
                                    <th class="remove-border col-md-3">نام و نام خانوادگی کارپرداز
                                    </th>
                                    <th class="remove-border col-md-3">نام و نام خانوادگی مسئول امور مالی
                                    </th>
                                    <th class="remove-border col-md-3">نام و نام خانوادگی رئیس واحد
                                    <th class="remove-border col-md-3">نام و نام خانوادگی رئیس دانشگاه
                                    </th>
                                </tr>
                                <tr>
                                    <th class="remove-border col-md-3">
                                        <input id="name" name="name" class="" required="required" type="text">
                                    </th>
                                    <th class="remove-border col-md-3">
                                        <input id="name" name="name" class="" required="required" type="text">
                                    </th>
                                    <th class="remove-border col-md-3">
                                        <input id="name" name="name" class="" required="required" type="text">
                                    <th class="remove-border col-md-3">
                                        &nbsp;<input id="name" name="name" class="" required="required" type="text">
                                    </th>
                                </tr>
                            </table>
                            <hr>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <button id="send" type="submit" class="btn btn-primary col-md-2 col-lg-offset-4">ثبت</button>
                                    <button id="send" type="submit" class="btn btn-info col-md-2">چاپ</button>
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
