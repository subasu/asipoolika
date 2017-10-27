@extends('layouts.adminLayout')
@section('content')
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>
                    @if(!empty($requests))
                        بررسی امور مربوط به درخواست ها
                    @endif
                    @if(!empty($request2))
                            بررسی امور مربوط به فاکتورها
                     @endif
                    </h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link" data-toggle="tooltip" title="جمع کردن"><i
                                        class="fa fa-chevron-up"></i></a>
                        </li>
                        <li><a class="close-link" data-toggle="tooltip" title="بستن"><i class="fa fa-close"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="col-md-12">
                    <div class="x_content">
                        <table style="direction:rtl;text-align: center" id="example"
                               class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <input type="hidden" id="token" value="{{ csrf_token() }}">
                            <thead>
                            <tr>
                                @if(!empty($requests))
                                    <th style="text-align: center" class="col-md-1">شناسه</th>
                                    <th style="text-align: center" class="">نوع درخواست</th>
                                    <th style="text-align: center" class="">واحد درخواست دهنده</th>
                                    <th style="text-align: center;border-right: 1px solid #e0e0d1" class="col-md-2">عملیات</th>
                                @endif
                                @if(!empty($request2))
                                    <th style="text-align: center" class="col-md-1"> شناسه</th>
                                    <th style="text-align: center" class="">نوع درخواست</th>
                                    <th style="text-align: center" class="">واحد درخواست دهنده</th>
                                    <th style="text-align: center;border-right: 1px solid #e0e0d1" class="col-md-2">عملیات</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody id="change">
                            @if(!empty($requests))
                                @foreach($requests as $request)
                                    <tr class="unit">
                                        <td>
                                            {{$request->id}}
                                        </td>
                                        <td>
                                            {{$request->requestType->title}}
                                        </td>
                                        <td>
                                            {{$request->unit->title}}
                                        </td>
                                        @if($request->request_type_id == 2)
                                            <td style="border-right: 1px solid #e0e0d1">
                                                <a href="{{url('admin/printServiceRequest/'.$request->id)}}" class="btn btn-primary">مشاهده جزئیات</a>
                                            </td>
                                        @endif
                                        @if($request->request_type_id == 3)
                                            <td style="border-right: 1px solid #e0e0d1">
                                                <a href="{{url('admin/printProductRequest/'.$request->id)}}" class="btn btn-primary">مشاهده جزئیات</a>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @endif
                            @if(!empty($request2))
                                @foreach($request2 as $request)
                                    <tr class="unit">
                                        <td>
                                            {{$request->id}}
                                        </td>
                                        <td>
                                            {{$request->requestType->title}}
                                        </td>
                                        <td>
                                            {{$request->unit->title}}
                                        </td>

                                        <td style="border-right: 1px solid #e0e0d1">
                                            <a href="{{url('user/showFactorDetails/'.$request->id)}}" class="btn btn-primary">مشاهده خلاصه تنظیمی</a>
                                        </td>

                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>


                </div>

            </div>


    {{--edit user's status by user-id --}}
@endsection

