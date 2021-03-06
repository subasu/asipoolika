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

<?php $user_info=\Illuminate\Support\Facades\Auth::user(); ?>
<input type="hidden" value="{{$user=\Illuminate\Support\Facades\Auth::user()}}">
<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                @if($pageName=='productCertificateManagement')
                    <h2 style="color:#005ce6;direction: rtl"><i class="fa fa-plus-square-o"></i> مدیریت گواهی های کالا تازه ثبت شده</h2>
                @elseif($pageName=='serviceCertificateManagement')
                    <h2  style="color:#005ce6;direction: rtl"><i class="fa fa-ban"></i>  مدیریت گواهی های خدمت تازه ثبت شده</h2>
                @elseif($pageName=='acceptedCertificateManagement')
                    <h2  style="color:#009900;direction: rtl"><i class="fa fa-check"></i> مدیریت گواهی های خدمت در حال پیگیری</h2>
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
                <table style="direction:rtl;text-align: center;font-size:16px;" id="example" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                    @if(!empty($pageName))
                        <thead>
                        <tr>
                            <th style="text-align: center ;">شناسه</th>
                            <th style="text-align: center ;">نام واحد</th>
                            <th style="text-align: center ;">درخواست دهنده</th>
                            <th style="text-align: center ;">شماره درخواست</th>
                            <th style="text-align: center ;">نوع درخواست</th>
                            @if($pageName=='productCertificateManagement')
                                {{--<th class="col-md-2" style="text-align: center ;">در انتطار بررسی</th>--}}
                                {{--<th class="col-md-2" style="text-align: center ;">درحال پیگیری</th>--}}
                                <th class="col-md-1" style="text-align: center ;border-right: 1px solid #d6d6c2;">عملیات</th>
                            @elseif($pageName=='serviceCertificateManagement')
                                {{--<th class="col-md-2" style="text-align: center ;">در انتطار بررسی</th>--}}
                                {{--<th class="col-md-2" style="text-align: center ;">درحال پیگیری</th>--}}
                                <th class="col-md-1" style="text-align: center ;border-right: 1px solid #d6d6c2;">عملیات</th>
                            @elseif($pageName=='acceptedCertificateManagement')
                                {{--<th class="col-md-2" style="text-align: center ;">در انتطار بررسی</th>--}}
                                {{--<th class="col-md-2" style="text-align: center ;">درحال پیگیری</th>--}}
                                {{--<th class="col-md-2" style="text-align: center ;">رد شده</th>--}}
                                {{--<th class="col-md-1" style="text-align: center ;">مشاهده</th>--}}
                                <th class="col-md-1" style="text-align: center ;border-right: 1px solid #d6d6c2;">وضعیت</th>
                                {{--<th class="col-md-2" style="text-align: center ;">مرحله</th>--}}
                                {{--<th class="col-md-1" style="text-align: center ;">عملیات</th>--}}
                            @elseif($pageName=='confirmProductRequest')
                                <th class="col-md-1" style="text-align: center ;">در انتظار بررسی</th>
                                <th class="col-md-1" style="text-align: center ;">دارای گواهی</th>
                                <th class="col-md-1" style="text-align: center ;">رد شده</th>
                                <th class="col-md-3" style="text-align: center ;border-right: 1px solid #d6d6c2;"> عملیات</th>
                            @endif

                        </tr>
                        </thead>
                    @endif
                    <tbody>
                    {{ csrf_field() }}
                    <input type="hidden" id="token" name="csrf-token" value="{{ csrf_token() }}">
                    @if($pageName=='productCertificateManagement')
                        @foreach($certificates as $certificate)
                            @if($certificate->request->request_type_id==3)
                            <tr>
                                    <td class="col-md-1">{{$certificate->id}}</td>
                                    <td class="col-md-2"> واحد {{$certificate->request->user->unit->title}}</td>
                                    <td class="col-md-2">{{$certificate->request->user->name .chr(10). $certificate->request->user->family}}</td>
                                    <td class="col-md-2">{{$certificate->request_id}}</td>
                                    <td class="col-md-2">{{$certificate->request_type}}</td>
                                    {{--<td class="info col-md-2">{{$certificate->certificate_record_count}}</td>--}}
                                    {{--<td class="success col-md-2">{{$certificate->certificate_record_count_accept}}</td>--}}
                                    <td style="border-right: 1px solid #d6d6c2;"><a class="btn btn-info col-md-12" href="{{url('admin/productCertificateRecords/'.$certificate->id)}}">مشاهده جزییات</a>
                            </tr>
                            @endif
                        @endforeach
                        @elseif($pageName=='serviceCertificateManagement')
                            @foreach($certificates as $certificate)
                                @if($certificate->request->request_type_id==2)
                                    <tr>
                                        <td class="col-md-1">{{$certificate->id}}</td>
                                        <td class="col-md-2"> واحد {{$certificate->request->user->unit->title}}</td>
                                        <td class="col-md-2">{{$certificate->request->user->name .chr(10). $certificate->request->user->family}}</td>
                                        <td class="col-md-2">{{$certificate->request_id}}</td>
                                        <td class="col-md-2">{{$certificate->request_type}}</td>
                                        {{--<td class="info col-md-2">{{$certificate->certificate_record_count}}</td>--}}
                                        {{--<td class="success col-md-2">{{$certificate->certificate_record_count_accept}}</td>--}}
                                        <td style="border-right: 1px solid #d6d6c2;"><a class="btn btn-info col-md-12"  href="{{url('admin/serviceCertificateRecords/'.$certificate->id)}}">مشاهده جزییات</a>
                                    </tr>
                                @endif
                            @endforeach
                    @elseif($pageName=='acceptedCertificateManagement')
                        @foreach($certificates as $certificate)
{{--                            @if($productRequest->request_record_count_accept>0)--}}
                                <tr>
                                    <td class="col-md-1">{{$certificate->id}}</td>
                                    <td class="col-md-2"> واحد {{$certificate->request->user->unit->title}}</td>
                                    <td class="col-md-2">{{$certificate->request->user->name .chr(10). $certificate->request->user->family}}</td>
                                    <td class="col-md-2">{{$certificate->request_id}}</td>
                                    <td class="col-md-2">{{$certificate->request_type}}</td>
                                    {{--<td class="info col-md-2">{{$certificate->certificate_record_count}}</td>--}}
                                    {{--<td class="success col-md-2">{{$certificate->certificate_record_count_accept}}</td>--}}
{{--                                    <td><a href="{{url('admin/productCertificateRecords/'.$certificate->id)}}" target="_blank" class="btn btn-info"><i class="fa fa-search"></i> جزئیات بیشتر</a></td>--}}
                                    <td style="padding-top:13px;border-right: 1px solid #d6d6c2;">
                                        @if($certificate->active==1)
                                            <span style="font-size: 16px;font-weight: lighter" class="label label-success">پایان یافته</span>
                                            @if($user_info->unit_id==9)
                                                @if($certificate->request->request_type_id==2)
                                                    <a href="{{url('admin/serviceDeliveryForm/'.$certificate->id)}}" class="btn btn-info bnt-lg" style="margin-top:8px;">نمایش گواهی</a>
                                                @elseif($certificate->request->request_type_id==3)
                                                    <a href="{{url('admin/exportDeliveryInstallCertificate/'.$certificate->id)}}" class="btn btn-info bnt-lg" style="margin-top:8px;">نمایش گواهی</a>
                                                @endif
                                            @endif
                                        @else
                                            <span style="font-size: 16px;font-weight: lighter;margin-bottom: 10px;" class="label label-warning">در حال رسیدگی </span>
                                        @endif
                                    </td>
                                </tr>
                            {{--@endif--}}
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
                                    <td class="col-md-2" style="font-size: 25px;border-right: 1px solid #d6d6c2;">
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
