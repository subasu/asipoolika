@extends('layouts.adminLayout')
@section('content')
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    @if($pageName=='issueBillManagement')
                        <h2 style="color:#005ce6;direction: rtl"><i class="fa fa-credit-card"></i> آپلود قبض انبار برای درخواست های کالا</h2>
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
                    <table style="direction:rtl;text-align: center;" id="example" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                        @if(!empty($pageName))
                            <thead>
                            <tr>
                                <th style="text-align: center ;">شناسه</th>
                                <th style="text-align: center ;">نام واحد</th>
                                <th style="text-align: center ;">درخواست دهنده</th>
                                @if($pageName=='issueBillManagement')
                                    <th class="col-md-5" style="text-align: center ;">خلاصه درخواست</th>
                                    <th class="col-md-1" style="text-align: center;border-right: 1px solid #d6d6c2">عملیات</th>
                                @endif
                            </tr>
                            </thead>
                        @endif
                        <tbody>
                        {{ csrf_field() }}
                        <input type="hidden" id="token" name="csrf-token" value="{{ csrf_token() }}">
                        @if($pageName=='issueBillManagement')
                            @foreach($requests as $request)
                                <tr>
                                    <td class="col-md-1">{{$request->id}}</td>
                                    <td class="col-md-2"> واحد {{$request->user->unit->title}}</td>
                                    <td class="col-md-2">{{$request->user->name .chr(10). $request->user->family}}</td>
                                    <td class="col-md-5" style="text-align:right;font-size: 18px;">
                                        <?php $i=1; ?>
                                        @foreach($request->records as $item)
                                            {{$i.' . '.$item->title}} |
                                            <?php $i++; ?>
                                        @endforeach
                                    </td>
                                    <td style="border-right: 1px solid #d6d6c2"><a class="btn btn-info"  href="{{url('admin/issueBill/'.$request->id)}}">آپلود قبض انبار</a>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

@endsection
