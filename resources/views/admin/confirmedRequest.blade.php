@extends('layouts.adminLayout')
@section('content')
    <style>
        table {font-size:18px;}
    </style>

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
                    @if($pageName=='confirmedRequestDetails')
                        <h2><i class="fa fa-list"></i> جزئیات درخواست شماره : {{$request[0]->id}} | ثبت شده توسط :   {{$request[0]->user->name}} {{$request[0]->user->family}} از واحد {{$request[0]->user->unit->title}} | <span style="color: tomato;font-weight: bold">تعداد رکوردهای فعال : {{$request[0]->request_records->count()}} رکورد</span></h2>
                    @endif
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link" data-toggle="tooltip" title="جمع کردن"><i class="fa fa-chevron-up"></i></a>
                        </li>
                        <li><a class="close-link" data-toggle="tooltip" title="بستن"><i class="fa fa-close"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                    <div class="row">
                        <div class="col-md-10">
                            <div class="well well-sm" style="text-align: right;direction: rtl;font-size:20px;"><i class="fa fa-user"></i> کارپرداز : @if($request[0]->supplier_id!=null) {{$request[0]->supplier->name .chr(10). $request[0]->supplier->family}} @else هنوز به کارپرداز ابلاغ نشده است @endif</div>
                            <table style="direction:rtl;text-align: center;" id="" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th style="text-align: center ;">شماره درخواست</th>
                                    <th style="text-align: center ;">نام واحد</th>
                                    <th style="text-align: center ;">درخواست دهنده</th>
                                    <th style="text-align: center ;border-right: 1px solid #d6d6c2">تاریخ ثبت</th>

                                </tr>
                                </thead>
                                <tbody>
                                {{ csrf_field() }}
                                <input type="hidden" id="token" name="csrf-token" value="{{ csrf_token() }}">
                                <tr>
                                    <td class="col-md-1">{{$request[0]->id}}</td>
                                    <td class="col-md-1">{{$request[0]->user->unit->title}}</td>
                                    <td class="col-md-1">{{$request[0]->user->name .chr(10). $request[0]->user->family}}</td>

                                    <td class="col-md-1" style="border-right: 1px solid #d6d6c2">{{$request[0]->date}}</td>

                                </tr>
                                </tbody>
                            </table>
                            <table style="direction:rtl;text-align: center;" id="" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <tbody>
                                {{ csrf_field() }}
                                <input type="hidden" id="token" name="csrf-token" value="{{ csrf_token() }}">
                                <tr>
                                    <th colspan="6" style="text-align:right"><i class="fa fa-list"></i> رکوردهای درخواست</th>
                                </tr>
                                <tr>
                                    <th style="text-align: center;">شناسه</th>
                                    <th style="text-align: center;">عنوان درخواست</th>
                                    <th style="text-align: center;">مقدار</th>
                                    <th style="text-align: center;">نرخ</th>
                                    <th style="text-align: center;border-right: 1px solid #d6d6c2">قیمت</th>
                                    <th style="text-align: center;border-right: 1px solid #d6d6c2">وضعیت</th>
                                </tr>
                                @foreach($request[0]->request_records as $record)
                                    <tr>
                                        <td>{{$record->id}}</td>
                                        <td class="col-md-2">{{$record->title}}</td>
                                        <td>{{$record->count}} {{$record->unit_count}}</td>
                                        <td>{{number_format($record->rate)}} تومان</td>
                                        <td>{{number_format($record->price)}} تومان</td>
                                        <td style="border-right: 1px solid #d6d6c2">
                                            @if($record->step==8) دارای گواهی
                                            @elseif($record->step==9) تحویل انبار
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-2">
                            <a href="{{url('admin/certificate/'.$request[0]->id)}}" class="btn btn-primary col-md-12 pull-right @if($request[0]->accept_count==0 or $request[0]->supplier_id==null) disabled @endif"> تحویل</a>
                            @if($request[0]->request_type_id == 3)
                                <a target="_blank" href="{{url('admin/printProductRequest/'.$request[0]->id)}}" class="btn btn-info col-md-12 pull-right"> چاپ درخواست </a>
                            @endif
                            @if($request[0]->request_type_id == 2)
                                <a href="{{url('admin/printServiceRequest/'.$request[0]->id)}}" class="btn btn-info col-md-12 pull-right"> چاپ درخواست </a>
                            @endif
                            @if($request[0]->supplier_id==null)
                                <a href="{{url('admin/impart/'.$request[0]->id)}}" class="btn btn-danger col-md-12 pull-right"> ابلاغ به کارپرداز</a>
                            @else
                                <a  class="btn btn-success disabled col-md-12 pull-right">ابلاغ شده</a>
                            @endif

                            <a href="{{url('admin/showCertificates/'.$request[0]->id)}}" class="btn btn-warning col-md-12 pull-right
                              ">گواهی ها</a>

                            {{--@if($productRequest->hasCertificate==1)--}}
                            {{--@if($productRequest->certificate->active==1)--}}
                            {{--<a href="{{url('admin/showCertificates/'.$productRequest->id)}}"--}}
                            {{--class="btn btn-warning col-md-5  pull-right">مشاهده  گواهی ها</a>--}}
                            {{--@endif--}}
                            {{--@endif--}}

                            @if(!empty($request[0]->bills[0]))
                                @if(count($request[0]->bills) >= 2 && $request[0]->bills[0]->active == 0 )
                                    <a href="{{url ('admin/preparedSummarize/'.$request[0]->id)}}" data-toggle=""  class="btn btn-info col-md-12  pull-right">ثبت خلاصه تنظیمی</a>
                                @endif
                                @if($request[0]->bills[0]->active == 1 && $request[0]->bills[0]->status == 0)
                                    <a title="این درخواست دارای خلاصه تنظیمی میباشد ، و در انتظار تایید کارپرداز میباشد" data-toggle=""  class="btn btn-default col-md-12  pull-right">بخوانید</a>
                                @endif
                                @if($request[0]->bills[0]->active == 1 && $request[0]->bills[0]->status == 1)
                                    <a href="{{url('admin/printFactors/'.$request[0]->id)}}" target="_blank"  class="btn btn-default col-md-12  pull-right">چاپ خلاصه تنظیمی <i class="fa fa-print"></i></a>
                                @endif
                                @if( $request[0]->supplier_id != null && $request[0]->bills[0]->active == 0)
                                    <a href="{{url('admin/issueBill/'.$request[0]->id)}}"  class="btn btn-default  col-md-12  pull-right" > آپلود فاکتور </a>
                                @endif
                                {{--@if($request[0]->request_type_id == 3 &&  $request[0]->supplier_id == null && $request[0]->bills[0]->active == 0)--}}
                                {{--<a   class="btn btn-default  col-md-12  pull-right"  disabled="disabled"> آپلود فاکتور </a>--}}
                                {{--@endif--}}
                            @endif
                            @if(empty($request[0]->bills[0]))
                                @if($request[0]->supplier_id != null)
                                    <a href="{{url('admin/issueBill/'.$request[0]->id)}}"  class="btn btn-default  col-md-12  pull-right" > آپلود فاکتور </a>
                                @endif
                                @if($request[0]->supplier_id == null)
                                    <a   class="btn btn-default  col-md-12  pull-right"  disabled="disabled"> آپلود فاکتور </a>
                                @endif
                            @endif
                            @if($request[0]->request_type_id == 3 && count($request[0]->warehouse) < 1 )
                                <a href="{{url('admin/warehouseBill/'.$request[0]->id)}}"  class="btn btn-danger  col-md-12  pull-right" >آپلود قبض انبار</a>
                            @endif
                            <a href="{{url('admin/costDocumentForm/'.$request[0]->id)}}" class="btn btn-primary col-md-12 pull-right">سند هزینه</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

@endsection
