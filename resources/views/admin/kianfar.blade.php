@extends('layouts.adminLayout')
@section('content')

    <!-- page content -->
    {{--<div class="right_col" role="main">--}}
        {{--<div class="">--}}
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2> مدیریت املاک و مشتریان</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link" data-toggle="tooltip" title="جمع کردن"><i class="fa fa-chevron-up"></i></a>
                                </li>
                                <li><a class="close-link" data-toggle="tooltip" title="بستن"><i class="fa fa-close"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="alert alert-info col-md-12 col-sm-12 col-xs-12" style="direction:rtl;font-size:17px;color:white;">

                        </div>

                        <div class="x_content">
                            <table style="direction:rtl;text-align: center" id="example" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th style="text-align: right">شناسه</th>
                                    <th style="text-align: right">دسته بندی ملک</th>
                                    <th style="text-align: right">نوع ملک</th>
                                    <th style="text-align: right">نوع معامله</th>
                                    <th style="text-align: right">متراژ</th>
                                    <th style="text-align: right">نام مالک</th>
                                    <th style="text-align: right">شماره همراه</th>
                                    <th style="text-align: right">عملیات</th>
                                </tr>
                                </thead>

                                <tbody>
                                    <tr>
                                        <td>35</td>
                                        <td>مسکونی</td>
                                        <td>آپارتمان مسکونی</td>
                                        <td>فروش</td>
                                        <td>200 متر مربع</td>
                                        <td>علیرضا مجیری</td>
                                        <td>09365552200</td>
                                        <td><button class="btn btn-success">ok</button>
                                            <button class="btn btn-danger"><i class="fa fa-ban"></i> Delete</button></td>
                                    </tr>
                                    <tr>
                                        <td>36</td>
                                        <td>مسکونی</td>
                                        <td>آپارتمان مسکونی</td>
                                        <td>فروش</td>
                                        <td>200 متر مربع</td>
                                        <td>علیرضا مجیری</td>
                                        <td>09365552200</td>
                                        <td><button class="btn btn-success">ok</button>
                                            <button class="btn btn-danger"><i class="fa fa-ban"></i> Delete</button></td>
                                    </tr>
                                    <tr>
                                        <td>37</td>
                                        <td>مسکونی</td>
                                        <td>آپارتمان مسکونی</td>
                                        <td>فروش</td>
                                        <td>200 متر مربع</td>
                                        <td>علیرضا مجیری</td>
                                        <td>09365552200</td>
                                        <td><button class="btn btn-success">ok</button>
                                            <button class="btn btn-danger"><i class="fa fa-ban"></i> Delete</button></td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        {{--</div>--}}
    {{--</div>--}}
@endsection