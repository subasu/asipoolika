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
                    <input type="hidden" value="{{$user->unit->title}}" content="{{$user->is_supervisor}}" name="user_unit_title" id="user_unit_title">
                    <h2><i class="fa fa-list"></i> لیست رکوردهای درخواست کالای شماره :  {{$requestRecords[0]->request_id}} | ثبت شده توسط :   {{$requestRecords[0]->request->user->name}} {{$requestRecords[0]->request->user->family}} از واحد {{$requestRecords[0]->request->user->unit->title}} | <span style="color: tomato;font-weight: bold">تعداد رکوردها : {{$requestRecords->count()}} رکورد</span></h2>
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
                        {{--                        @if($user->unit->title=='تدارکات')--}}
                        <th style="text-align: center ;">نرخ</th>
                        {{--@endif--}}
                        <th style="text-align: center ;">قیمت</th>
                        <th class="col-md-3" style="text-align: center ;">عملیات</th>
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
                            <td><button class="btn btn-link btn-round" data-toggle="tooltip" title="{{$requestRecord->description}}"> توضیحات
                                </button>
                                <input id="add_to_list" content="{{$requestRecord->id}}" name="{{$requestRecord->request_id}}" type="button" class="btn btn-danger" required value="به این گواهی اضافه شود" />
                        </tr>
                    @endforeach
                    {{--</form>--}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title" style="direction: rtl">
                        <h2><i class="fa fa-newspaper-o"></i> صدور گواهی</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link" data-toggle="tooltip" title="جمع کردن"><i class="fa fa-chevron-up"></i></a>
                        </li>
                        <li><a class="close-link" data-toggle="tooltip" title="بستن"><i class="fa fa-close"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="col-md-12 col-sm-8 col-xs-12">
                        <div class="x_content">
                            <div class="row" style="direction: rtl;text-align: right;margin-bottom: 20px;font-size:20px;">
                                <div class="col-md-12"> بدینوسیله گواهی می شود خدمات انجام شده توسط شرکت / فروشگاه
                                    <input id="shop_comp" name="shop_comp"
                                           placeholder="" required="required" type="text" style="width: 20%;padding:2px 5px 2px 5px;"> به واحد
                                    <span style="color:red">{{$users[0]->unit->title}}</span> به آقای / خانم
                                    <select name="" id="" style="font-size: 18px;padding:2px 5px 2px 5px;">
                                        @foreach($users as $user)
                                        <option value="{{$user->id}}">{{$user->name}} {{$user->family}}</option>
                                        @endforeach
                                    </select> تحویل گردید و پرداخت شده است.
                                </div>
                            </div>

                            <form class="form-horizontal form-label-left product" novalidate id="product">
                                {!! csrf_field() !!}
                                <input type="hidden" value="{{$user}}" name="user_id" id="user_id">
                                <input type="hidden" value="0" name="record_count" id="record_count">

                                <table class="table table-bordered mytable" dir="rtl">
                                    <thead>
                                    <tr>
                                        <th class="col-md-1">ردیف</th>
                                        {{--<th>کد کالا</th>--}}
                                        <th class="col-md-3">شرح</th>
                                        <th class="col-md-1">تعداد / مقدار</th>
                                        <th class="col-md-3">مبلغ کل</th>
                                        <th class="col-md-1">حذف</th>
                                    </tr>
                                    </thead>
                                    <tbody id="table-row">

                                    </tbody>
                                </table>
                            </form>
                            <div class="form-group">
                                <div class="col-md-8">
                                    <button id="save_request" name="save_request" type="button" class="btn btn-primary col-md-6 col-md-offset-6"> صدور گواهی
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var count=0;
        var record_count=0;
        $('#add_to_list').click(function(){
            count++;
            var row_id='row'+count;
            var select_id='select'+count;
            var product_title = $('#product_title').val();
            var product_count = $('#product_count').val();

                var row='<tr id="'+row_id+'">'+
                        '<th scope="row">'+count+'</th>'+
                        '<td>'+'<input style="padding-right:5px;" class="required form-control" type="text" name="product_title[]" value="'+$('#product_title').val()+'">'+'</td>'+
                        '<td>'+'<input style="padding-right:5px;" class="required form-control" type="number" name="product_count[]" value="'+$('#product_count').val()+'">'+'</td>'+
                        '<td>'+$.trim($("#unit_count option:selected").text())+'</td>'+
                        '<input type="text" name="unit_count" value="'+$.trim($("#unit_count option:selected").text())+'">'+
                        '<td class="col-md-9">'+
                        '<input id="product_details" class="form-control" name="product_details[]" placeholder=""  value="'+$('#product_details').val()+'" type="text" >'+'</td>'+
                        '<input type="hidden" value="'+$.trim($("#unit_count option:selected").text())+'" name="unit_count_each[]">'+
                        '<td>'+
                        '<a type="button" class="btn btn-danger remove_row" data-toggle="tooltip" title="حذف" style="font-size:18px;">'+
                        '<span class="fa fa-trash"></span>'+
                        '</a>'+
                        '</td>'+
                        '</tr>';
                $('#table-row').append(row);
                record_count++;
                $('#record_count').val(record_count);

        });
        $(document).on('click','.remove_row', function(){
            $(this).closest('tr').remove();
            record_count--;
            $('#record_count').val(record_count);
        });
    </script>

@endsection
