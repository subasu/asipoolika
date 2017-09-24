@extends('layouts.adminLayout');
@section('content')


    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>کارت های صادر شده کارگران</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link" data-toggle="tooltip" title="جمع کردن"><i
                                        class="fa fa-chevron-up"></i></a>
                        </li>
                        <li><a class="close-link" data-toggle="tooltip" title="بستن"><i class="fa fa-close"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class=""  style="direction:rtl;font-size:17px;color:white;">
                    <label>جستجو بر اساس بازه تاریخ</label>
                    <input type="text" class="form-control"/>
                    <input type="text" class="form-control"/>
                    <input type="button" value="جستجو" class="btn btn-default">
                </div>
                <div class="x_content">
                    <table style="direction:rtl;text-align: center" id="example"
                           class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">

                        <thead>
                        <tr>
                            <th style="text-align: center">ردیف</th>
                            <th style="text-align: center">نام و نام خانوادگی کارگر</th>
                            <th style="text-align: center">نام و نام خانوادگی درخواست دهنده</th>
                            <th style="text-align: center">مشاهده عکس کارت</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    @endsection
