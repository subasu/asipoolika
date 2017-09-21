@extends('layouts.adminLayout1')
@section('content')
    <input type="hidden" value="{{$user_id=\Illuminate\Support\Facades\Auth::user()->id}}">
    <div class="page-title">
        <div class="title_right">
            <h3>
                درخواست خرید کالا
            </h3>
        </div>
    </div>
    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2> فرم شماره 1
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

                <div class="col-md-12 col-sm-8 col-xs-12">
                    <div class="x_content">
                        {{--<input type="hidden" value="3" name="request_type_id" id="request_type_id">--}}
                        <table class="table table-bordered mytable" dir="rtl">
                            <thead>
                            <tr>
                                {{--<th>کد کالا</th>--}}
                                <th>عنوان کالا</th>
                                <th>تعداد / مقدار</th>
                                <th>واحد سنجش</th>
                                <th>توضیحات</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                {{--<td>--}}
                                {{--<input id="name" class="form-control col-md-7 col-xs-12" name="name"--}}
                                {{--placeholder="" required="required" type="text">--}}
                                {{--</td>--}}
                                <td class="col-md-3">
                                    <input id="product_title" class="form-control req" name="product_title"
                                           placeholder="نام کالا مورد نیاز" required="required" type="text"></td>
                                <td class="col-md-2">
                                    <input id="product_count" class="form-control req" name="product_count"
                                           placeholder="" required="required" type="number" min="0">
                                </td>
                                <td class="col-md-2">
                                    <select id="unit_count" class="form-control req" name="unit_count[]"
                                            required="required">
                                    </select>
                                </td>
                                <td class="col-md-9">
                                    <input id="product_details" class="form-control" name="product_details"
                                           placeholder="" required="required" type="text" >
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <div class="col-md-8">
                                <button id="add_to_list" type="button"
                                        class="btn btn-primary col-md-6 col-md-offset-6"> به لیست اضافه شود
                                </button>
                            </div>
                        </div>

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
                <div class="col-md-12 col-sm-8 col-xs-12">
                    <div class="x_content">
                        <form class="form-horizontal form-label-left product" novalidate id="product">
                            {!! csrf_field() !!}
                            <input type="hidden" value="{{$user_id}}" name="user_id" id="user_id">
                            <input type="hidden" value="0" name="record_count" id="record_count">

                            <table class="table table-bordered mytable" dir="rtl">
                                <thead>
                                <tr>
                                    <th class="col-md-1">ردیف</th>
                                    {{--<th>کد کالا</th>--}}
                                    <th class="col-md-2">عنوان کالا</th>
                                    <th class="col-md-1">تعداد / مقدار</th>
                                    <th class="col-md-1">واحد سنجش</th>
                                    <th class="col-md-4">توضیحات</th>
                                    <th class="col-md-1">حذف</th>
                                </tr>
                                </thead>
                                <tbody id="table-row">

                                </tbody>
                            </table>
                        </form>
                        <div class="form-group">
                            <div class="col-md-8">
                                <button id="save_request" name="save_request" type="button" class="btn btn-success col-md-6 col-md-offset-6"> ثبت نهایی
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                {{--! end tables --}}
            </div>
        </div>
    </div>
@endsection
