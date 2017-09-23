@extends('layouts.adminLayout');
@section('content')
    <div class="page-title">
        <div class="title_right">
            <h3>
                درخواست خدمت
            </h3>
        </div>

    </div>
    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2> فرم شماره 2

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
                <div class="col-md-2 col-sm-2 col-xs-12"></div>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <div class="x_content">
                        <form class="form-horizontal form-label-left" novalidate>
                            <table class="table table-bordered mytable" dir="rtl">
                                <thead>
                                <tr>
                                    <th>واحد اقدام کننده</th>
                                    <th>واحد متقاضی</th>
                                    <th>خدمت</th>
                                    <th>برآورد مقدار</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><select id="name" class="form-control col-md-7 col-xs-12" name="name" required="required">
                                            <option value="">واحد 1</option>
                                        </select></td>
                                    <td><select id="name" class="form-control col-md-7 col-xs-12" name="name" required="required">
                                            <option value="">واحد 1</option>
                                        </select></td>
                                    <td><input id="name" class="form-control col-md-7 col-xs-12" name="name"
                                               placeholder="" required="required" type="text"></td>
                                    <td><input id="name" class="form-control col-md-7 col-xs-12" name="name"
                                               placeholder="" required="required" type="text"></td>
                                </tr>
                                </tbody>
                            </table>
                            <div class="form-group">
                                <div class="col-md-8 ">
                                    <button id="send" type="submit" class="btn btn-primary col-md-2">ثبت</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-2 col-sm-2 col-xs-12"></div>
            </div>
            <div class="x_panel">
                <div class="x_title">
                    <h2> درخواست نهایی

                    </h2>
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

                        <table class="table table-bordered mytable" dir="rtl">
                            <thead>
                            <tr>
                                <th>ردیف</th>
                                <th>واحد اقدام کننده</th>
                                <th>واحد متقاضی</th>
                                <th>عنوان خدمت</th>
                                <th>مفدار</th>
                                <th>حذف</th>
                                <th>ویرایش</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <th scope="row">1</th>
                                <td>222</td>
                                <td>333</td>
                                <td>عدد</td>
                                <td>3</td>
                                <td><i class="fa fa-remove color-red"></i></td>
                                <td><i class="fa fa-edit color-info"></i></td>
                            </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
                {{--! end tables --}}
            </div>
        </div>
@endsection
