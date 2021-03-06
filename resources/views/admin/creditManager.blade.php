@extends('layouts.adminLayout');
@section('title')
    درخواست خدمت
@endsection
@section('content')


    <!-- Modal -->
    <form>
        {{csrf_field()}}
        <div id="commentModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title" style="direction: rtl; font-size: 20px;">رد درخواست</h4>
                    </div>
                    <div class="modal-body">
                        <h4 style="direction: rtl; font-size: 20px;">لطفا دلیل رد درخواست را بطور کامل تایپ کنید.</h4>
                        <textarea class="form-control" id="whyNot" name="whyNot" placeholder=""></textarea>
                    </div>
                    <div class="modal-footer">
                        <button style="margin-left:40%; width: 30%;font-size: 20px;" type="button" class="btn btn-primary" id="sub" data-dismiss="modal">ثبت</button>
                    </div>
                </div>
                <input type="hidden" id="token" value="{{ csrf_token() }}">
            </div>
        </div>
    </form>


    {{--@if(count($requests))--}}
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>مشاهده  درخواست ها</h2>
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
                        <table style="direction:rtl;text-align: center" id="table" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th style="text-align: center ;">عنوان درخواست</th>
                                <th style="text-align: center ;">نام واحد</th>
                                <th style="text-align: center ;">نام درخواست دهنده</th>
                                <th style="text-align: center ;">نرخ (به ریال)</th>
                                <th style="text-align: center ;">قیمت</th>
                                <th style="text-align: center ;">پیگیری درخواست</th>
                                <th style="text-align: center ;">رد کردن درخواست</th>
                            </tr>
                            </thead>

                            <tbody>
                            <form id="">
                                {{ csrf_field() }}
                                <input type="hidden" id="token" name="csrf-token" value="{{ csrf_token() }}">
                                @foreach($requests as $request)
                                    <tr>
                                        <td>{{$request->title}}</td>
                                        <td>{{$request->request->user->unit->title}}</td>
                                        <td>{{$request->request->user->name .chr(10). $request->request->user->family}}</td>
                                        <td>{{number_format($request->rate)}}</td>
                                        <td>{{number_format($request->price)}}</td>
                                        <td><input id="acceptRequest" content="{{$request->id}}"  type="button" class="btn btn-success" required value="پیگیری درخواست" /></td>
                                        <td><input id="refuseRequest" content="{{$request->id}}"  type="button" class="btn btn-danger"  required value="رد کردن درخواست" /></td>
                                    </tr>
                                @endforeach()
                            </form>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


    {{--@endif--}}

    {{--@if(!count($requests))--}}
        {{--<div class="row">--}}
            {{--<div class="col-md-12 col-sm-12 col-xs-12">--}}
                {{--<div class="x_panel">--}}
                    {{--<div class="x_title" align="center">--}}
                        {{--<h1>داده ای برای نمایش وجود ندارد</h1>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--@endif()--}}

@endsection