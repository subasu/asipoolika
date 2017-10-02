@extends('layouts.adminLayout')
@section('content')

        <!-- Modal -->
<div id="commentModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="direction: ltr; font-size: 20px;">رد درخواست</h4>
            </div>
            <div class="modal-body" style="direction: rtl;text-align:right;">
                <label for="comment" style="direction: rtl; font-size: 20px;">لطفا دلیل رد درخواست را بطور کامل تایپ کنید.</label>
                <textarea style="" class="form-control" id="comment" name="comment"></textarea>
            </div>
            <div class="modal-footer  col-md-12">
                <button style="margin-left:40%; width: 30%;font-size: 20px;" type="button" class="btn btn-primary" id="sub" data-dismiss="modal">ثبت</button>
            </div>
        </div>
        <input type="hidden" id="token" value="{{ csrf_token() }}">
    </div>
</div>


<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                @if($pageName=='certificateManagement')
                    <h2 style="color:#005ce6;direction: rtl"><i class="fa fa-plus-square-o"></i> مدیریت گواهی های تازه ثبت شده</h2>
                @elseif($pageName=='refusedProductRequestManagement')
                    <h2  style="color:#e60000;direction: rtl"><i class="fa fa-ban"></i> مدیریت درخواست های کالای رد شده</h2>
                @elseif($pageName=='acceptProductRequestManagement')
                    <h2  style="color:#009900;direction: rtl"><i class="fa fa-check"></i> مدیریت درخواست های کالا در حال پیگیری</h2>
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
                            <th style="text-align: center ;">شماره درخواست</th>
                            @if($pageName=='certificateManagement')
                                <th class="col-md-2" style="text-align: center ;">در انتطار بررسی</th>
                                <th class="col-md-2" style="text-align: center ;">درحال پیگیری</th>
                                {{--<th class="col-md-2" style="text-align: center ;">رد شده</th>--}}
                                <th class="col-md-1" style="text-align: center ;">عملیات</th>
                            @elseif($pageName=='refusedProductRequestManagement')
                                <th class="col-md-1" style="text-align: center ;">رکوردهای رد شده</th>
                                {{--<th style="text-align: center ;">عملیات</th>--}}
                            @elseif($pageName=='acceptProductRequestManagement')
                                <th class="col-md-2" style="text-align: center ;">در انتطار بررسی</th>
                                <th class="col-md-2" style="text-align: center ;">درحال پیگیری</th>
                                <th class="col-md-2" style="text-align: center ;">رد شده</th>
                                <th class="col-md-1" style="text-align: center ;">وضعیت</th>
                                {{--<th class="col-md-2" style="text-align: center ;">مرحله</th>--}}
                                {{--<th class="col-md-1" style="text-align: center ;">عملیات</th>--}}
                            @elseif($pageName=='confirmProductRequest')
                                <th class="col-md-1" style="text-align: center ;">در انتظار بررسی</th>
                                <th class="col-md-1" style="text-align: center ;">دارای گواهی</th>
                                <th class="col-md-1" style="text-align: center ;">رد شده</th>
                                <th class="col-md-3" style="text-align: center ;"> عملیات</th>
                            @endif

                        </tr>
                        </thead>
                    @endif
                    <tbody>
                    {{ csrf_field() }}
                    <input type="hidden" id="token" name="csrf-token" value="{{ csrf_token() }}">
                    @if($pageName=='certificateManagement')
                        @foreach($certificates as $certificate)
                            <tr>
                                {{--@if($productRequest->request_record_count>0)--}}
                                    <td class="col-md-1">{{$certificate->id}}</td>
                                    <td class="col-md-2"> واحد {{$certificate->request->user->unit->title}}</td>
                                    <td class="col-md-2">{{$certificate->request->user->name .chr(10). $certificate->request->user->family}}</td>
                                    <td class="col-md-2">{{$certificate->request_id}}</td>
                                    <td class="info col-md-2">{{$certificate->certificate_undecided_count}}</td>
                                    <td class="success col-md-2">{{$certificate->certificate_accepted_count}}</td>
                                    {{--<td class="danger col-md-2">{{$productRequest->request_record_count_refused}}</td>--}}
                                    <td><a class="btn btn-info" href="{{url('admin/certificateRecords/'.$certificate->id)}}">مشاهده جزییات</a>
                                {{--@endif--}}
                            </tr>
                        @endforeach
                    @elseif($pageName=='refusedProductRequestManagement')
                        @foreach($productRequests as $productRequest)
                            {{--                                        @if($productRequest->request_refuse_count>0)--}}
                            <tr>
                                <td class="col-md-1">{{$productRequest->id}}</td>
                                <td class="col-md-2"> واحد {{$productRequest->user->unit->title}}</td>
                                <td class="col-md-2">{{$productRequest->user->name .chr(10). $productRequest->user->family}}</td>
                                <td class="col-md-2 danger">{{$productRequest->refuse_record_count}}</td>
                                {{--<td>بررسی مجدد</td>--}}
                            </tr>
                            {{--@endif--}}
                        @endforeach
                    @elseif($pageName=='acceptProductRequestManagement')
                        @foreach($productRequests as $productRequest)
                            @if($productRequest->request_record_count_accept>0)
                                <tr>
                                    <td class="col-md-1">{{$productRequest->id}}</td>
                                    <td class="col-md-2"> واحد {{$productRequest->user->unit->title}}</td>
                                    <td class="col-md-2">{{$productRequest->user->name .chr(10). $productRequest->user->family}}</td>
                                    <td class="info col-md-2">{{$productRequest->request_record_count}}</td>
                                    <td class="success col-md-2">{{$productRequest->request_record_count_accept}}</td>
                                    <td class="danger col-md-2">{{$productRequest->request_record_count_refused}}</td>
                                    <td class="col-md-2">@if($productRequest->active==1) Finish @else In Proccess @endif</td>
                                    {{--<td><a class="btn btn-info" href="{{url('admin/productRequestRecords/'.$productRequest->id)}}">مشاهده جزییات</a>--}}
                                </tr>
                            @endif
                        @endforeach
                    @elseif($pageName=='confirmProductRequest')
                        @foreach($productRequests as $productRequest)
                            {{--                                        @if($productRequest->all_count==($productRequest->accept_count+$productRequest->refuse_count))--}}
                            @if($productRequest->active==1)
                                <tr>
                                    <td class="col-md-1">{{$productRequest->id}}</td>
                                    <td class="col-md-1"> واحد {{$productRequest->user->unit->title}}</td>
                                    <td class="col-md-1">{{$productRequest->user->name .chr(10). $productRequest->user->family}}</td>
                                    <td class="info col-md-1">{{$productRequest->accept_count}}</td>
                                    <td class="success col-md-1">{{$productRequest->has_certificate_count}}</td>
                                    <td class="danger col-md-1">{{$productRequest->refuse_count}}</td>
                                    <td class="col-md-2" style="font-size: 25px;">
                                        {{--<a href="" class="btn btn-danger">صدور صورتجلسه تحویل و نصب</a>--}}
                                        <a href="{{url('admin/certificate/'.$productRequest->id)}}" class="btn btn-primary col-md-10"> صدور گواهی</a>
                                        {{--<button type="button" class="btn btn-default" data-toggle="tooltip" title="چاپ گواهی">--}}
                                        {{--<span class="fa fa-print" style="font-size: 20px;"></span>--}}
                                        {{--</button>--}}
                                    </td>
                                    `   </tr>
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