@extends('layouts.adminLayout')
@section('content')
    <input type="hidden" value="{{$user_id=\Illuminate\Support\Facades\Auth::user()->id}}">
    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2> مدیریت امضاها
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

                <div class="col-md-12 col-sm-8 col-xs-12" style="font-size: 16px;">
                    <div class="x_content">
                        <table style="direction:rtl;text-align: center" id="datatable-responsive"
                               class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                               width="100%">
                            <thead>
                            <tr>
                                <th style="text-align: center" class="col-md-1">شناسه</th>
                                <th style="text-align: center" class="col-md-2">مالک امضاء</th>
                                <th style="text-align: center" class="col-md-2">سمت</th>
                                <th style="text-align: center" class="col-md-2">نوع امضاء</th>
                                {{--<th style="text-align: center">تصویر</th>--}}
                                {{--<th style="text-align: center">تصویر کاربری</th>--}}
                                <th class="col-md-1" style="text-align: center">عملیات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($signatures as $signature)
                                <tr>
                                    <td>{{$signature->id}}</td>
                                    <td>{{$signature->user->name}} {{$signature->user->family}}</td>
                                    <td>{{$signature->title}}</td>
                                    <td style="font-size:20px;">
                                        @if($signature->force==1)
                                        <span class="label label-danger col-md-12">اجباری</span>
                                        @else
                                        <span class="label label-info col-md-12">اختیاری</span>
                                    @endif
                                    </td>
                                    {{--<td><img class="col-md-2" src="http://s9.picofile.com/file/8306682392/blesing_cover_.jpg" style="padding:0;"/></td>--}}
                                    <td>
                                        <a href="http://s9.picofile.com/file/8306682392/blesing_cover_.jpg" target="_blank" type="button"
                                           class="btn btn-round btn-default" data-toggle="tooltip" title="نمایش امضا">
                                            <span class="fa fa-search"></span>
                                        </a>
                                        <a href="{{url('customer/editPropertyShow/')}}" type="button"
                                           class="btn btn-round btn-success" data-toggle="tooltip" title="ویرایش امضاء">
                                            <span class="fa fa-pencil"></span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-2 col-sm-2 col-xs-12"></div>
            </div>
        </div>

@endsection
