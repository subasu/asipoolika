@extends('layouts.adminLayout');
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
                    <textarea class="form-control" id="comment" name="comment" placeholder=""></textarea>
                </div>
                <div class="modal-footer">
                    <button style="margin-left:40%; width: 30%;font-size: 20px;" type="button" class="btn btn-primary" id="sub" data-dismiss="modal">ثبت</button>
                </div>
            </div>
            <input type="hidden" id="token" value="{{ csrf_token() }}">
        </div>
    </div>
</form>

<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2> مدیریت درخواست های خدمت تازه ثبت شده</h2>
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
                <table style="direction:rtl;text-align: center" id="example" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th style="text-align: center ;">شناسه</th>
                        <th style="text-align: center ;">نام واحد</th>
                        <th style="text-align: center ;">درخواست دهنده</th>
                        <th style="text-align: center ;"></th>
                    </tr>
                    </thead>
                    <tbody>
                    {{ csrf_field() }}
                    <input type="hidden" id="token" name="csrf-token" value="{{ csrf_token() }}">
                    @foreach($serviceRequests as $serviceRequest)
                        <tr>
                            <td class="col-md-1">{{$serviceRequest->id}}</td>
                            <td> واحد {{$serviceRequest->user->unit->title}}</td>
                            <td>{{$serviceRequest->user->name .chr(10). $serviceRequest->user->family}}</td>
                            <td><a class="btn btn-info" href="{{url('admin/productRequestInfo/'.$serviceRequest->id)}}">مشاهده جزییات</a>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{--</form>--}}
            </div>
        </div>
    </div>

@endsection
