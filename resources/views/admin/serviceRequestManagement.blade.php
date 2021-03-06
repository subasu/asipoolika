@extends('layouts.adminLayout')
@section('content')

    <div id="productRequestManagementModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content" style="text-align: right;direction: rtl;font-size: larger">
                <div class="modal-header">
                    <button type="button" class="close pull-left" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-question-circle"></i> راهنمای مدیریت درخواست های تازه ثبت شده</h4>
                </div>
                <div class="modal-body" style="text-align: justify">
                    <p>مراحل کار : </p>
                    <ol>
                        <li>روی دکمه مشاهده جزئیات کلیک کنید و ردیف های مربوط به هر درخواست را مشاهده کنید</li>
                    </ol>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary col-md-12" data-dismiss="modal">متوجه شدم</button>
                </div>
            </div>

        </div>
    </div>

<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                @if($pageName=='productRequestManagement')
                    <h2 style="color:#005ce6;direction: rtl"><i class="fa fa-plus-square-o"></i> مدیریت  درخواست های سرویس تازه درج شده
                        <button type="button" class="btn btn-sample">
                            <i class="fa fa-question-circle" style="font-size: 20px;" data-toggle="modal" data-target="#productRequestManagementModal" title="راهنمای استفاده" data-placement="right"></i>
                        </button>
                    </h2>
                @elseif($pageName=='refusedProductRequestManagement')
                    <h2  style="color:#e60000;direction: rtl"><i class="fa fa-ban"></i> مدیریت درخواست های سرویس رد شده
                    </h2>
                @elseif($pageName=='acceptProductRequestManagement')
                    <h2  style="color:#009900;direction: rtl"><i class="fa fa-check"></i> مدیریت درخواست های سرویس در حال پیگیری
                    </h2>
                @elseif($pageName=='confirmProductRequest')
                    <h2  style="color:#cc0099;direction: rtl"><i class="fa fa-check"></i> مدیریت درخواست های تایید شده</h2>
                @endif
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link" data-toggle="tooltip" title="جمع کردن"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li><a class="close-link" data-toggle="tooltip" title="بستن"><i class="fa fa-close"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            {{--<div class="alert alert-info col-md-12 col-sm-12 col-xs-12" style="direction:rtl;font-size:17px;color:white;">تعداد درخواست ها :  {{$productRequests->count()}} عدد--}}

            {{--</div>--}}
            <div class="x_content">
                {{--<form id="serviceDetailForm">--}}
                <table style="direction:rtl;text-align: center;" id="example" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                    @if(!empty($pageName))
                        <thead>
                        <tr>
                            <th style="text-align: center ;">شناسه</th>
                            <th style="text-align: center ;">نام واحد</th>
                            <th style="text-align: center ;">درخواست دهنده</th>
                            <th style="text-align: center ;">تاریخ ثبت</th>
                            @if($pageName=='productRequestManagement')
                                <th class="col-md-2" style="text-align: center ;">در انتظار بررسی</th>
                                <th class="col-md-2" style="text-align: center ;">درحال پیگیری</th>
                                <th class="col-md-2" style="text-align: center ;">رد شده</th>
                                <th class="col-md-1" style="text-align: center ;border-right: 1px solid #e0e0d1">عملیات</th>
                            @elseif($pageName=='refusedProductRequestManagement')
                                <th class="col-md-1" style="text-align: center ;">رکوردهای رد شده</th>
                                {{--<th style="text-align: center ;">عملیات</th>--}}
                            @elseif($pageName=='acceptProductRequestManagement')
                                <th class="col-md-2" style="text-align: center ;">در انتظار بررسی</th>
                                <th class="col-md-2" style="text-align: center ;">درحال پیگیری</th>
                                <th class="col-md-2" style="text-align: center ;">رد شده</th>
                                <th class="col-md-2" style="text-align: center ;"></th>
                                <th class="col-md-1" style="text-align: center ;border-right: 1px solid #e0e0d1">وضعیت</th>
                                {{--<th class="col-md-2" style="text-align: center ;">مرحله</th>--}}
                                {{--<th class="col-md-1" style="text-align: center ;">عملیات</th>--}}
                            @elseif($pageName=='confirmProductRequest')
                                <th class="col-md-1" style="text-align: center ;">در انتظار بررسی</th>
                                <th class="col-md-1" style="text-align: center ;">دارای گواهی</th>
                                <th class="col-md-3" style="text-align: center ;border-right: 1px solid #e0e0d1"> عملیات</th>
                            @endif

                        </tr>
                        </thead>
                    @endif
                    <tbody>
                    {{ csrf_field() }}
                    <input type="hidden" id="token" name="csrf-token" value="{{ csrf_token() }}">
                    @if($pageName=='productRequestManagement')
                        @foreach($serviceRequests as $productRequest)
                            <tr>
                                @if($productRequest->request_record_count>0)
                                    <td class="col-md-1">{{$productRequest->id}}</td>
                                    <td class="col-md-2"> واحد {{$productRequest->user->unit->title}}</td>
                                    <td class="col-md-2">{{$productRequest->user->name .chr(10). $productRequest->user->family}}</td>
                                    <td class="col-md-1">{{$productRequest->date}}</td>
                                    <td class="info col-md-2">{{$productRequest->request_record_count}}</td>
                                    <td class="success col-md-2">{{$productRequest->request_record_count_accept}}</td>
                                    <td class="danger col-md-2">{{$productRequest->request_record_count_refused}}</td>
                                    <td style="border-right: 1px solid #e0e0d1"><a class="btn btn-info" href="{{url('admin/serviceRequestRecords/'.$productRequest->id)}}">مشاهده جزییات</a>
                                @endif
                            </tr>
                        @endforeach
                    @elseif($pageName=='refusedProductRequestManagement')
                        @foreach($serviceRequests as $productRequest)
                            {{--                                        @if($productRequest->request_refuse_count>0)--}}
                            <tr>
                                <td class="col-md-1">{{$productRequest->id}}</td>
                                <td class="col-md-2"> واحد {{$productRequest->user->unit->title}}</td>
                                <td class="col-md-2">{{$productRequest->user->name .chr(10). $productRequest->user->family}}</td>
                                <td class="col-md-1">{{$productRequest->date}}</td>
                                <td class="col-md-2 danger">{{$productRequest->refuse_record_count}}</td>
                                {{--<td>بررسی مجدد</td>--}}
                            </tr>
                            {{--@endif--}}
                        @endforeach
                    @elseif($pageName=='acceptProductRequestManagement')
                        @foreach($serviceRequests as $productRequest)
                            @if($productRequest->request_record_count_accept>0)
                                <tr>
                                    <td class="col-md-1">{{$productRequest->id}}</td>
                                    <td class="col-md-2"> واحد {{$productRequest->user->unit->title}}</td>
                                    <td class="col-md-2">{{$productRequest->user->name .chr(10). $productRequest->user->family}}</td>
                                    <td class="col-md-1">{{$productRequest->date}}</td>
                                    <td class="info col-md-2">{{$productRequest->request_record_count}}</td>
                                    <td class="success col-md-2">{{$productRequest->request_record_count_accept}}</td>
                                    <td class="danger col-md-2">{{$productRequest->request_record_count_refused}}</td>
                                    <td><a class="btn btn-info" href="{{url('admin/acceptedRequestRecords/'.$productRequest->id)}}">مشاهده جزییات</a>
                                    <td class="col-md-2" style="padding-top: 20px;border-right: 1px solid #e0e0d1">@if($productRequest->active==1)<span style="font-size: 16px;font-weight: lighter;" class="label label-success">پایان یافته</span>@else <span style="font-size: 16px;font-weight: lighter;" class="label label-warning">در حال رسیدگی </span>@endif</td>
                                    {{--<td><a class="btn btn-info" href="{{url('admin/productRequestRecords/'.$productRequest->id)}}">مشاهده جزییات</a>--}}
                                </tr>
                            @endif
                        @endforeach
                    @elseif($pageName=='confirmProductRequest')
                        @foreach($serviceRequests as $productRequest)
                            {{--                                        @if($productRequest->all_count==($productRequest->accept_count+$productRequest->refuse_count))--}}
                            @if($productRequest->active==1)
                                <tr>
                                    <td class="col-md-1">{{$productRequest->id}}</td>
                                    <td class="col-md-1"> واحد {{$productRequest->user->unit->title}}</td>
                                    <td class="col-md-1">{{$productRequest->user->name .chr(10). $productRequest->user->family}}</td>
                                    <td class="col-md-1">{{$productRequest->date}}</td>
                                    <td class="info col-md-1">{{$productRequest->accept_count}}</td>
                                    <td class="success col-md-1">{{$productRequest->has_certificate_count}}</td>
                                    <td class="col-md-2" style="font-size: 25px;border-right: 1px solid #e0e0d1">
                                        {{--<a href="" class="btn btn-danger">صدور صورتجلسه تحویل و نصب</a>--}}
                                        <a href="{{url('admin/certificate/'.$productRequest->id)}}" class="btn btn-primary col-md-10  @if($productRequest->accept_count==0 or $productRequest->supplier_id==null) disabled @endif"> صدور گواهی</a>

                                        <a href="{{url('admin/printProductRequest/'.$productRequest->id)}}" class="btn btn-info col-md-10"> چاپ درخواست </a>
                                        @if($productRequest->supplier_id==null)
                                            <a href="{{url('admin/impart/'.$productRequest->id)}}" class="btn btn-danger col-md-10"> ابلاغ به کارپرداز</a>
                                        @else
                                            <span class="label label-success col-md-10" style="font-size:17px;padding:7px 0 7px 0;font-weight: lighter;margin-bottom: 5px;">ابلاغ شده</span>
                                        @endif
                                        <a href="{{url('admin/impart/'.$productRequest->id)}}"  class="btn btn-warning col-md-10">چاپ گواهی</a>
                                        {{--<button type="button" class="btn btn-default" data-toggle="tooltip" title="چاپ گواهی">--}}
                                        {{--<span class="fa fa-print" style="font-size: 20px;"></span>--}}
                                        {{--</button>--}}
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    @endif
                    </tbody>
                </table>
                {{--</form>--}}
            </div>
        </div>
    </div>

@endsection
