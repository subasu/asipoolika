@extends('layouts.adminLayout')
@section('content')
        <!-- Modal -->
<div id="why_not_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" style="font-size: 20px;">&times;</button>
                <h4 class="modal-title">رد رکورد درخواست</h4>
            </div>
            <div class="modal-body" style="text-align: right">
                <label for="why_not" style="font-size: 20px;">دلیل خود برای رد این درخواست را بنویسید تا به اطلاع درخواست کننده برسد</label>
                 <textarea id="why_not" style="text-align: right" maxlength="300" required="required" class="form-control why_not" name="why_not" data-parsley-trigger="keyup" data-parsley-minlength="20" data-parsley-maxlength="300" data-parsley-minlength-message="شما حداقل باید 20 کاراکتر وارد کنید"
                           data-parsley-validation-threshold="10"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" id="why_not_btn" content=""  name="" class="btn btn-primary col-md-12">ثبت دلیل</button>
            </div>
        </div>

    </div>
</div>

<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title" style="direction: rtl">
                @if(!empty($requestRecords[0]))
                    <input type="hidden" value="{{$requestRecords[0]->id}}" name="request_id">

                    @if($requestRecords[0]->request->request_type_id==3)
                        <h2><i class="fa fa-list"></i> لیست رکوردهای درخواست کالای شماره :  {{$requestRecords[0]->request_id}} | ثبت شده توسط :   {{$requestRecords[0]->request->user->name}} {{$requestRecords[0]->request->user->family}} از واحد {{$requestRecords[0]->request->user->unit->title}} | <span style="color: tomato;font-weight: bold">تعداد رکوردها : {{$requestRecords->count()}} رکورد</span></h2>
                    @elseif($requestRecords[0]->request->request_type_id==2)
                        <h2><i class="fa fa-list"></i> لیست رکوردهای درخواست خدمت شماره : {{$requestRecords[0]->request_id}} | ثبت شده توسط :   {{$requestRecords[0]->request->user->name}} {{$requestRecords[0]->request->user->family}} از واحد {{$requestRecords[0]->request->user->unit->title}} | <span style="color: tomato;font-weight: bold">تعداد رکوردها : {{$requestRecords->count()}} رکورد</span></h2>
                    @endif
                @endif
                {{--<h2>لیست رکوردهای درخواست کالای شماره : {{$requestRecords[0]->request_id}}</h2>--}}
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link" data-toggle="tooltip" title="جمع کردن"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li><a class="close-link" data-toggle="tooltip" title="بستن"><i class="fa fa-close"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <table style="direction:rtl;text-align: center;font-size: 16px;" id="table" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th style="text-align: center ;">شناسه</th>
                        <th style="text-align: center ;">عنوان درخواست</th>
                        <th style="text-align: center ;">مقدار</th>
                        <th style="text-align: center ;">نرخ</th>
                        <th style="text-align: center ;">قیمت</th>
                        <th class="col-md-3" style="text-align: center ;">توضیحات</th>
                        <th class="col-md-3" style="text-align: center ;">مرحله</th>
                    </tr>
                    </thead>
                    <tbody>
                    {{--<form id="serviceDetailForm">--}}
                    {{ csrf_field() }}
                    <input type="hidden" id="token" name="csrf-token" value="{{ csrf_token() }}">
                    @foreach($requestRecords as $requestRecord)
                        <tr>
                            <input type="hidden" value="{{$requestRecord->id}}" class="record_id">
                            <th style="text-align: center">{{$requestRecord->id}}</th>
                            <td>{{$requestRecord->title}}</td>
                            <td id="count" content="{{$requestRecord->count}}">{{$requestRecord->count}} {{$requestRecord->unit_count}}</td>
                            <input type="hidden" class="count" value="{{$requestRecord->count}}" name="count">
                            {{--<input type="hidden" class="" value="2000" name="count[]">--}}
                            <td>{{number_format($requestRecord->rate)}} تومان</td>
                            <td>{{number_format($requestRecord->price)}} تومان</td>
                            <td>{{$requestRecord->description}}</td>
                            <td>{{$requestRecord->status}}</td>
                        </tr>
                    @endforeach
                    {{--</form>--}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
