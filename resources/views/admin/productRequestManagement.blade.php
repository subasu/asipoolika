@extends('layouts.adminLayout')
@section('content')

        <!-- Modal -->
<div id="myModal" class="modal fade" role="dialog" style="direction: rtl;text-align: right">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">نحوه مدیریت درخواست های تایید شده</h4>
            </div>
            <div class="modal-body">
                <p>درخواست های تایید شده درخواست هایی هستند که کلیه مراحل اداری را طی کرده اند.</p>
                <p>مراحل انجام کار : </p>
                <ol>
                    <li>ابلاغ درخواست به کارپرداز</li>
                    <li>تحویل فوری</li>
                </ol>
                <p>در این حین میتوانید درخواست را بطور کامل مشاهده و در صورت نیاز چاپ کنید</p>
                <p>همچنین میتوانید خلاصه تنظیمی مربوط به آن را نیز چاپ کنید</p>
                <p>پس از صدور گواهی وقتی گواهی روند اداری اش را بطور کامل طی کردی میتوانید آن را مشاهده و در صورت نیاز چاپ کنید</p>
                <span style="color:red">توجه : </span> چنانچه دکمه ای غیرفعال است به این معناست که پیش نیازهای لازم برای آن هنوز انجام نشده است. مثلا اگر دکمه صدور گواهی غیرفعال است به این دلیل است که هنوز درخواست به یک کارپرداز واگذار نشده است.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info col-md-12" data-dismiss="modal">متوجه شدم</button>
            </div>
        </div>

    </div>
</div>
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
                        @if($pageName=='productRequestManagement')
                            <h2 style="color:#005ce6;direction: rtl"><i class="fa fa-plus-square-o"></i> مدیریت درخواست های کالا تازه ثبت شده</h2>
                        @elseif($pageName=='refusedProductRequestManagement')
                            <h2  style="color:#e60000;direction: rtl"><i class="fa fa-ban"></i> مدیریت درخواست های کالای رد شده</h2>
                        @elseif($pageName=='acceptProductRequestManagement')
                            <h2  style="color:#009900;direction: rtl"><i class="fa fa-check"></i> مدیریت درخواست های کالا در حال پیگیری</h2>ss
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
                        @if($pageName=='confirmProductRequest')
                            {{--<a href="{{url('admin/confirmProductRequestManagement')}}" class="btn btn-danger col-md-12"><i class="fa fa-refresh"></i> بروزرسانی درخواست ها</a>--}}
                            {{--<a href="{{url('admin/confirmServiceRequestManagement')}}" class="btn btn-danger col-md-12"><i class="fa fa-refresh"></i> بروزرسانی درخواست ها</a>--}}
                            {{--<button  style="font-size:18px;" type="button" class="btn btn-primary col-md-12" data-toggle="modal" data-target="#myModal">نحوه کار با این صفحه <i class="fa fa-question-circle-o" aria-hidden="true"></i></button>--}}

                            @endif
                        {{--<form id="serviceDetailForm">--}}
                        <table style="direction:rtl;text-align: center;font-size:16px;" id="example" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
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
                                        <th class="col-md-2" style="text-align: center;">رد شده</th>
                                        <th class="col-md-1" style="text-align: center;border-right: 1px solid #d6d6c2">عملیات</th>
                                        {{--<td></td>--}}
                                    @elseif($pageName=='refusedProductRequestManagement')
                                        <th class="col-md-1" style="text-align: center ;">رکوردهای رد شده</th>
                                        {{--<th style="text-align: center ;">عملیات</th>--}}
                                    @elseif($pageName=='acceptProductRequestManagement')
                                        <th class="col-md-2" style="text-align: center ;">در انتظار بررسی</th>
                                        <th class="col-md-2" style="text-align: center ;">درحال پیگیری</th>
                                        <th class="col-md-2" style="text-align: center ;">رد شده</th>
                                        <th class="col-md-1" style="text-align: center ;"></th>
                                        <th class="col-md-1" style="text-align: center ;border-right: 1px solid #e0e0d1">وضعیت</th>
                                        {{--<td></td>--}}
                                        {{--<th class="col-md-2" style="text-align: center ;">مرحله</th>--}}
                                        {{--<th class="col-md-1" style="text-align: center ;">عملیات</th>--}}
                                    @elseif($pageName=='confirmProductRequest')
                                        <th class="col-md-2" style="text-align: center ;">در انتظار بررسی</th>
                                        <th class="col-md-1" style="text-align: center ;">دارای گواهی</th>
                                        <th class="col-md-1" style="text-align: center ;">خلاصه وضعیت</th>
                                        <th class="col-md-3" style="text-align: center ;border-right: 1px solid #e0e0d1"> مشاهده جزئیات</th>
                                        {{--<td></td>--}}
                                    @endif
                                    {{--<td></td>--}}
                            </tr>
                            </thead>
                            @endif
                            <tbody>
                                {{ csrf_field() }}
                                <input type="hidden" id="token" name="csrf-token" value="{{ csrf_token() }}">
                                @if($pageName=='productRequestManagement')
                                    @foreach($productRequests as $productRequest)
                                    <tr>
                                        @if($productRequest->request_record_count>0)
                                            <td class="col-md-1">{{$productRequest->id}}</td>
                                            <td class="col-md-2"> واحد {{$productRequest->user->unit->title}}</td>
                                            <td class="col-md-2">{{$productRequest->user->name .chr(10). $productRequest->user->family}}</td>
                                            <td class="col-md-1">23 خرداد 96</td>
                                            <td class="info col-md-2">{{$productRequest->request_record_count}}</td>
                                            <td class="success col-md-2">{{$productRequest->request_record_count_accept}}</td>
                                            <td class="danger col-md-2">{{$productRequest->request_record_count_refused}}</td>
                                            <td><a class="btn btn-info"  href="{{url('admin/productRequestRecords/'.$productRequest->id)}}">مشاهده جزییات</a>
                                            </td>
                                        @endif
                                    </tr>
                                    @endforeach
                                @elseif($pageName=='refusedProductRequestManagement')
                                    @foreach($productRequests as $productRequest)
                                     <tr>
                                        <td class="col-md-1">{{$productRequest->id}}</td>
                                        <td class="col-md-2"> واحد {{$productRequest->user->unit->title}}</td>
                                        <td class="col-md-2">{{$productRequest->user->name .chr(10). $productRequest->user->family}}</td>
                                         <td class="col-md-1">23 خرداد 96</td>
                                        <td class="col-md-2 danger">{{$productRequest->refuse_record_count}}</td>
                                     </tr>
                                    @endforeach
                                @elseif($pageName=='acceptProductRequestManagement')
                                    @foreach($productRequests as $productRequest)
                                        @if($productRequest->request_record_count_accept>0)
                                        <tr>
                                            <td class="col-md-1">{{$productRequest->id}}</td>
                                            <td class="col-md-2"> واحد {{$productRequest->user->unit->title}}</td>
                                            <td class="col-md-2">{{$productRequest->user->name .chr(10). $productRequest->user->family}}</td>
                                            <td class="col-md-1">23 خرداد 96</td>
                                            <td class="info col-md-2">{{$productRequest->request_record_count}}</td>
                                            <td class="success col-md-2">{{$productRequest->request_record_count_accept}}</td>
                                            <td class="danger col-md-2">{{$productRequest->request_record_count_refused}}</td>
                                            <td><a class="btn btn-info"  href="{{url('admin/acceptedRequestRecords/'.$productRequest->id)}}">مشاهده جزییات</a>
                                            <td class="col-md-2" style="padding-top: 17px;border-right: 1px solid #e0e0d1">@if($productRequest->active==1)<span style="font-size: 15px;padding: 4px 7px 4px 7px;font-weight: lighter;" class="label label-success">پایان یافته</span>@else <span style="font-size: 16px;font-weight: lighter" class="label label-warning">در حال رسیدگی </span>@endif</td>
                                            {{--<td></td>--}}
                                            {{--<td><a class="btn btn-info" href="{{url('admin/productRequestRecords/'.$productRequest->id)}}">مشاهده جزییات</a>--}}
                                        </tr>
                                        @endif
                                    @endforeach
                                @elseif($pageName=='confirmProductRequest')
                                    @if($productRequests[0]->request_type_id==3)
                                    <a href="{{url('admin/confirmProductRequestManagement')}}" class="btn btn-danger col-md-12">بروزرسانی درخواست ها</a>
                                    @elseif($productRequests[0]->request_type_id==2)
                                    <a href="{{url('admin/confirmServiceRequestManagement')}}" class="btn btn-danger col-md-12">بروزرسانی درخواست ها</a>
                                    @endif
{{--                                    @if(!empty($productRequests))--}}
                                    @foreach($productRequests as $productRequest)
{{--                                        @if($productRequest->all_count==($productRequest->accept_count+$productRequest->refuse_count))--}}
                                        @if($productRequest->active==1)
                                    <tr>
                                        <td class="col-md-1">{{$productRequest->id}}</td>
                                        <td class="col-md-1">{{$productRequest->user->unit->title}}</td>
                                        <td class="col-md-1">{{$productRequest->user->name .chr(10). $productRequest->user->family}}</td>
                                        <td class="col-md-1">23 خرداد 96</td>
                                        <td class="info col-md-1">{{$productRequest->accept_count}}</td>
                                        <td class="success col-md-1">{{$productRequest->has_certificate_count}}</td>
                                        <td class="col-md-2" style="text-align: right;direction: rtl;font-size:15px;">
                                                <ul> <li>کارپرداز :
                                                    @if($productRequest->supplier_id!=null)
                                                        {{$productRequest->supplier->name .chr(10). $productRequest->supplier->family}}
                                                        @else <span style="color:red">ابلاغ نشده</span>
                                                    @endif
                                                        </li>
                                                    <li>گواهی :
                                                    @if($productRequest->certificate!=null) <span style="color:#006600;font-weight: bold">دارد</span>
                                                        @else <span style="color:red">ندارد</span>
                                                        @endif
                                                    </li>
                                                    <li>سند هزینه :
                                                    @if($productRequest->costDocument!= null)<span style="color:#006600;font-weight: bold">تنظیم شده</span>
                                                        @else <span style="color:red">ندارد</span>
                                                    @endif</li>
                                                </ul>
                                         </td>
                                        <td class="col-md-1" style="font-size: 25px;border-right: 1px solid #d6d6c2;">
                                            <a href="{{url('admin/confirmedRequestDetails/'.$productRequest->id)}}" class="btn btn-primary col-md-12">مشاهده جزئیات</a>
                                        </td>
                                    </tr>
                                        @endif
                                    @endforeach
                                {{--@endif--}}
                                @endif
                            </tbody>
                        </table>
                        {{--</form>--}}
                    </div>
                </div>
            </div>

@endsection
