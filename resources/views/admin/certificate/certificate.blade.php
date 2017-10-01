@extends('layouts.adminLayout')
@section('content')

<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title" style="direction: rtl">
                @if(!empty($requestRecords[0]))
                    <input type="hidden" value="{{$requestRecords[0]->id}}" name="request_id">
                    <input type="hidden" value="{{$user->unit->title}}" content="{{$user->is_supervisor}}" name="user_unit_title" id="user_unit_title">
                    <h2><i class="fa fa-list"></i> لیست رکوردهای درخواست کالای شماره :  {{$requestRecords[0]->request_id}} | ثبت شده توسط :   {{$requestRecords[0]->request->user->name}} {{$requestRecords[0]->request->user->family}} از واحد {{$requestRecords[0]->request->user->unit->title}} | <span style="color: tomato;font-weight: bold">تعداد رکوردها : {{$requestRecords->count()}} رکورد</span></h2>
                    <input type="hidden" value="{{$requestRecords->count()}}" id="record_counts">
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
                <table style="direction:rtl;text-align: center;font-size: 16px;" id="table" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th style="text-align: center ;">شناسه</th>
                        <th style="text-align: center ;">عنوان درخواست</th>
                        <th style="text-align: center ;">مقدار</th>
                        <th style="text-align: center ;">نرخ</th>
                        <th style="text-align: center ;">قیمت</th>
                        <th class="col-md-3" style="text-align: center ;">عملیات</th>
                    </tr>
                    </thead>
                    <tbody id="main_table">

                    {{ csrf_field() }}
                    <input type="hidden" id="token" name="csrf-token" value="{{ csrf_token() }}">
                    @foreach($requestRecords as $requestRecord)
                        <tr>
                            <input type="hidden" value="{{$requestRecord->id}}" class="record_id">
                            <th style="text-align: center">{{$requestRecord->id}}</th>
                            <td>{{$requestRecord->title}}</td>
                            <input type="hidden" value="{{$requestRecord->title}}" id="record_title" class="record_title" name="">
                            <td id="count" content="{{$requestRecord->count}}">{{$requestRecord->count}} {{$requestRecord->unit_count}}</td>
                            <input type="hidden" class="unit_count" value="{{$requestRecord->unit_count}}" name="unit_count">
                            <input type="hidden" class="record_count2" value="{{$requestRecord->count}}" name="count">
                            <td>{{number_format($requestRecord->rate)}} تومان</td>
                            <input type="hidden" value="{{$requestRecord->rate}}" id="record_rate" class="record_rate" name="">
                            <td>{{number_format($requestRecord->price)}} تومان</td>
                            <input type="hidden" value="{{$requestRecord->price}}" id="record_price" class="record_price" name="">
                            <td>
                            <input id="add_to_list" content="{{$requestRecord->id}}" name="{{$requestRecord->request_id}}" type="button" class="btn btn-danger add_to_list" value="به این گواهی اضافه شود" />
                            <input type="hidden" value="{{$requestRecord->request_id}}" class="request_id">
                            </td>
                        </tr>
                    @endforeach
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
                                <input type="hidden" class="unit_id" value="{{$users[0]->unit->id}}">
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
                                <table style="direction:rtl;text-align: center;font-size: 16px;" id="table" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th style="text-align: center ;">ردیف</th>
                                        <th style="text-align: center ;">شرح</th>
                                        <th style="text-align: center ;">تعداد / مقدار</th>
                                        <th style="text-align: center ;">مبلغ کل</th>
                                        <th class="col-md-3" style="text-align: center ;">حذف</th>
                                    </tr>
                                    </thead>
                                    <tbody id="table-row">
                                    {{ csrf_field() }}
                                    <input type="hidden" id="token" name="csrf-token" value="{{ csrf_token() }}">

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
        function formatNumber (num) {
            return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
        }
    </script>
    <script>
        var count=0;
        var record_count=0;


        $('table .add_to_list').on('click',function(e){
//        $(document).on('click','.add_to_list',function(){
//            $('.add_to_list').on('click',function(){
                count++;
                var record_id = $(this).parents('tr').find('.record_id').val();
                var record_title = $(this).parents('tr').find('.record_title').val();
                var record_price = $(this).parents('tr').find('.record_price').val();
                var record_count2 = $(this).parents('tr').find('.record_count2').val();
                var unit_count = $(this).parents('tr').find('.unit_count').val();
                var record_rate = $(this).parents('tr').find('.record_rate').val();
                var request_id = $(this).parents('tr').find('.request_id').val();
                var row=
                        '<tr>'+
                            '<input type="hidden" value="'+record_id+'" class="record_id">'+
                            '<td>'+count+'</td>'+
                            '<td>'+record_title+'</td>'+
                            '<input type="hidden" value="'+record_title+'" id="record_title" class="record_title" name="">'+
                            '<td>'+record_count2+'</td>'+
                            '<input type="hidden" class="record_count2" value="'+record_count2+'" name="count">'+
                            '<input type="hidden" class="unit_count" value="'+unit_count+'" name="unit_count">'+
                            '<input type="hidden" class="record_rate" value="'+record_rate+'" name="record_rate">'+
                            '<td>'+formatNumber(record_price)+' تومان'+'</td>'+
                            '<input type="hidden" value="'+record_price+'" id="record_price" class="record_price" name="">'+
                            '<td><a type="button" class="btn btn-danger remove_row" data-toggle="tooltip" title="حذف" style="font-size:18px;"><span class="fa fa-trash"></span></a></td>'+
                            '<input type="hidden" value="'+request_id+'" class="request_id">'+
                        '</tr>';

                $('#table-row').append(row);
//                record_count++;
//                $('#record_count').val(record_count);
                $(this).closest('tr').remove();
            });

        $(document).on('click','.remove_row', function(){

            record_count--;
            $('#record_count').val(record_count);
            var record_id = $(this).parents('tr').find('.record_id').val();
            var record_title = $(this).parents('tr').find('.record_title').val();
            var record_price = $(this).parents('tr').find('.record_price').val();
            var record_count2 = $(this).parents('tr').find('.record_count').val();
            var unit_count = $(this).parents('tr').find('.unit_count').val();
            var record_rate = $(this).parents('tr').find('.record_rate').val();
            var request_id = $(this).parents('tr').find('.request_id').val();

                var row=
                        '<tr>'+
                            '<input type="hidden" value="'+record_id+'" class="record_id">'+
                            '<th style="text-align: center">'+record_id+'</th>'+
                            '<td>'+record_title+'</td>'+
                            '<input type="hidden" value="'+record_title+'" id="record_title" class="record_title" name="">'+
                            '<td id="count" content="'+record_count2+'">'+record_count2+' '+unit_count+'</td>'+
                            '<input type="hidden" class="unit_count" value="'+unit_count+'" name="unit_count">'+
                            '<input type="hidden" class="record_count" value="'+record_count2+'" name="count">'+
                            '<td>'+formatNumber(record_rate)+' تومان</td>'+
                            '<input type="hidden" value="'+record_rate+'" id="record_rate" class="record_rate" name="">'+
                            '<td>'+formatNumber(record_price)+' تومان</td>'+
                            '<input type="hidden" value="'+record_price+'" id="record_price" class="record_price" name="">'+
                            '<td>'+
                            '<input id="add_to_list" content="'+record_id+'" name="'+request_id+'" type="button" class="btn btn-danger add_to_list" required value="به این گواهی اضافه شود" />'+
                            '<input type="hidden" value="'+request_id+'" class="request_id">'+
                            '</td>'+
                        '</tr>';
            $('#main_table').append(row);
            $(this).closest('tr').remove();
            count--;
        });
    </script>

@endsection
