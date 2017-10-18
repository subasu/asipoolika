@extends('layouts.adminLayout')
@section('content')
<style>
    input[type=checkbox] {
        margin-top: 20%;
        -ms-transform: scale(1.5); /* IE */
        -moz-transform: scale(1.5); /* FF */
        -webkit-transform: scale(1.5); /* Safari and Chrome */
        -o-transform: scale(1.5); /* Opera */

        ms-filter: contrast(150%);
        -moz-filter: contrast(150%);
        -webkit-filter: contrast(150%);
        -o-filter: contrast(150%);
        filter: contrast(150%);
    }

</style>
<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title" style="direction: rtl">
                @if(!empty($requestRecords[0]))
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
                <div class="alert alert-danger" style="direction: rtl;text-align:right;font-size:18px;">
                    <a href="#" data-toggle="popover" title="بستن" class="pull-left" data-dismiss="alert" aria-label="close" style="font-size:20px;color:white"><i class="fa fa-times" aria-hidden="true"></i></a>
                    <strong>روش کار : </strong> گزینه هایی که قصد صدور آن ها در یک گواهی را دارید انتخاب کرده و روی دکمه مربوطه کلیک کنید.
                </div>
                <form id="form_certificate">
                    @if(!empty($requestRecords[0]))
                        <input type="hidden" value="{{$requestRecords[0]->request_id}}" id="requestId" name="requestId">
                    @endif
                    <div class="row" style="font-size: 18px;direction: rtl;text-align: right;margin-bottom: 10px;">
                        <div class="col-md-12"> بدینوسیله گواهی می شود خدمات انجام شده توسط شرکت / فروشگاه
                            <input id="shop_comp" name="shop_comp"
                                   placeholder="" required="required" type="text" style="width: 20%;padding:2px 5px 2px 5px;"> به واحد
                            <span style="color:red">{{$users[0]->unit->title}}</span> به آقای / خانم
                            <select name="receiver_id"  style="font-size: 18px;padding:2px 5px 2px 5px;">
                                @foreach($users as $user2)
                                    <option name ='receiverId' value="{{$user2->id}}">{{$user2->name}} {{$user2->family}}</option>
                                @endforeach
                            </select> تحویل گردید و پرداخت شده است.
                        </div>
                    </div>
                <table style="direction:rtl;text-align: center;font-size: 16px;" id="table" class="table table-responsive table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th class="col-md-1" style="text-align: center ;">انتخاب</th>
                        <th class="col-md-1" style="text-align: center ;">ردیف</th>
                        <th class="col-md-1" style="text-align: center ;">شرح</th>
                        <th style="text-align: center ;">مقدار</th>
                        <th style="text-align: center ;">مقدار واقعی</th>
                        <th style="text-align: center ;">نرخ</th>
                        <th style="text-align: center ;">نرخ واقعی</th>
                        <th style="text-align: center ;">قیمت</th>
                        <th style="text-align: center ;">قیمت واقعی</th>
                        {{--<th class="col-md-3" style="text-align: center ;">عملیات</th>--}}
                    </tr>
                    </thead>
                    <tbody id="main_table">
                    {{--{{ csrf_field() }}--}}

                    <?php $row=1; ?>

                    @foreach($requestRecords as $requestRecord)
                        <tr id="{{$requestRecord->id}}" value="0" class="tr" name="tr">
                            <input type="hidden" value="{{$requestRecord->id}}" class="record_id" name="record_id[]">
                            <td style="text-align: center">
                               <input type="checkbox" id="record_ch" value="{{$requestRecord->id}}" class="record_ch" name="record">  {{--{{$requestRecord->id}}--}}
                            </td>
                            <td>{{$row}}</td>
                            <?php $row++; ?>
                            <td>{{$requestRecord->title}}</td>
                            <input type="hidden" value="{{$requestRecord->title}}" id="record_title" class="record_title" name="">

                            <td class="gray3" id="count" content="{{$requestRecord->count}}">{{$requestRecord->count}} {{$requestRecord->unit_count}}</td>
                            <td class="gray3" ><input type="text" class="form-control new_count" id="new_count"  name="new_count[]"/></td>
                            <input type="hidden" class="record_count2" value="{{$requestRecord->count}}" name="count">
                            <input type="hidden" class="unit_count" value="{{$requestRecord->unit_count}}" name="unit_count[]">

                            <td class="gray2">{{number_format($requestRecord->rate)}} تومان</td>
                            <td class="gray2"><input type="text" class="form-control new_rate" id="new_rate"  name="new_rate[]"/></td>
                            <input type="hidden" value="{{$requestRecord->rate}}" id="record_rate" class="record_rate" name="">

                            <td class="gray1">{{number_format($requestRecord->price)}} تومان</td>
                            <td class="gray1"><input type="text" class="form-control new_price" id="new_price" content="content" name="new_price[]" style="font-size:16px;color:red"/></td>
                            <input type="hidden" value="{{$requestRecord->price}}" id="record_price" class="record_price" name="">
                            <input type="hidden" value="" id="new_price2" class="new_price2" name="new_price2[]">

                        </tr>
                    @endforeach
                    <input type="hidden" id="token" value="{{ csrf_token() }}">
                    <input type="hidden" value="0" name="checked_count" id="checked_count">
                    <input type="hidden" value="" name="certificate_type" id="certificate_type">
                    <input type="hidden" value="" name="" id="receiverId">
                    </tbody>
                </table>

                </form>
                <div class="row">
                    <div class="col-md-12 col-md-offset-3">
                        @if($requestRecords[0]->request->request_type_id==3)
                        <button id="use_certificate" content="2" class="btn btn-danger col-md-3">صدور گواهی تحویل و مصرف</button>
                        <button id="install_certificate"  content="1" class="btn btn-primary col-md-3">صدور گواهی تحویل و نصب</button>
                        @elseif($requestRecords[0]->request->request_type_id==2)
                        <button id="service_certificate"  content="3" class="btn btn-success col-md-4 col-md-offset-1">صدور گواهی انجام خدمت</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function formatNumber (num) {
            return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
        }

        $('.new_rate').on('keyup', function() {
            var rate=$(this).parents('tr').find('.new_rate').val();
            var count=$(this).parents('tr').find('.new_count').val();

            var price = rate * count;
            $(this).parents('tr').find('.new_price').val(formatNumber(price));
            $(this).parents('tr').find('.new_price2').val(price);

//            var price=$(this).parents('tr').find('.price').val();
            price = price.replace(/,/g, '');
        });
    </script>
<script>

</script>
<script>



</script>
    <script>
//        var checked_count;
//        $(".record_ch").click(function() {
//            if(this.checked) {
//                checked_count=$('#checked_count').val();
//                checked_count++;
//                $('#checked_count').val(checked_count);
//            }
//            if(!(this.checked)) {
//                checked_count=$('#checked_count').val();
//                checked_count--;
//                $('#checked_count').val(checked_count);
//            }
//        });
        $(document).on('click','#install_certificate',function(){

            var recordId  = '';
            var newRate   = '';
            var newPrice  = '';
            var newCount  = '';
            var unitCount = '';
            var token = $('#token').val();
            var requestId = $('#requestId').val();
            var certificateType = $(this).attr('content');
            $("[name = 'record']:checked").each(function(){

               recordId  += $(this).val()+',';
               newRate   += $(this).parents('tr').find('.new_rate').val()+',';
               newCount  += $(this).parents('tr').find('.new_count').val()+',';
               unitCount += $(this).parents('tr').find('.unit_count').val()+',';
               newPrice += $(this).parents('tr').find('.new_price').val().replace(/,/g , '')+',';
            });

            var receiverId = '';
            $("[name='receiverId']:selected").each(function(){
                receiverId += $(this).val();
                $('#receiverId').val(receiverId);
            });

            var shop_comp = $('#shop_comp').val();
            if(shop_comp == '' || shop_comp == null)
            {
                $('#shop_comp').css('border' , 'red 4px solid');
                $('#shop_comp').focus();
                return false;
            }
            if ($('input.record_ch').is(':checked'))
            {
                $("[name = 'record']:checked").each(function(){
                    var td    = $(this);
                    var rate  = $(this).parents('tr').find('.new_rate').val();
                    var price = $(this).parents('tr').find('.new_price').val();
                    var count = $(this).parents('tr').find('.new_count').val();
                    if(count == '' || count == null )
                    {
                        $(this).parents('tr').find('.new_count').css('border' , 'red 4px solid' );
                        $(this).parents('tr').find('.new_count').focus();
                        return false;
                    }
                    else if(rate == '' || rate == null)
                    {
                        $(this).parents('tr').find('.new_rate').css('border' , 'red 4px solid' );
                        $(this).parents('tr').find('.new_rate').focus();
                        return false;
                    }
                    else if(price == '' || price == null )
                    {
                        $(this).parents('tr').find('.new_price').css('border' , 'red 4px solid' );
                        $(this).parents('tr').find('.new_price').focus();
                        return false;
                    }
                    else
                        {

                            swal({
                                    title: "آیا از ثبت درخواست مطمئن هستید؟",
                                    text: "",
                                    type: "warning",
                                    showCancelButton: true,
                                    confirmButtonColor: "	#5cb85c",
                                    cancelButtonText: "خیر ، منصرف شدم",
                                    confirmButtonText: "بله ثبت شود",
                                    closeOnConfirm: true,
                                    closeOnCancel: false
                                },
                                function (isConfirm) {
                                    if (isConfirm) {
                                        //serialize() send all form input values
                                        //var formData = $('#form_certificate').serialize();
//                                        console.log(formData);
//                                        return false;
//                                        $.ajaxSetup({
//                                            headers: {
//                                                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
//                                            }
//                                        });
                                        $.ajax({
                                            url: "{{ url('admin/execute_certificate') }}",
                                            type: 'POST',
                                            //dataType: 'json',
                                            data:
                                                {
                                                    'recordId': recordId,
                                                    'newRate' : newRate ,
                                                    'newPrice' : newPrice,
                                                    'newCount':newCount,
                                                    '_token' : token,
                                                    'unitCount':unitCount,
                                                    'receiverId':receiverId,
                                                    'certificateType' : certificateType,
                                                    'shop_comp'       : shop_comp,
                                                    'requestId'      : requestId
                                                },

                                            //context : recordId,
                                            success: function (response) {
                                               // $(td).parentsUntil(DOM,'tr').hide();
                                                console.log(response);
                                                swal
                                                ({
                                                    title: 'گواهی ثبت شد',
                                                    text:"گواهی به لیست گواهی ها اضافه شد",
                                                    type:'success',
                                                    confirmButtonText: "بستن"
                                                });
                                                setInterval(function(){ window.location.reload(true); }, 1000);

                                            },
                                            error: function (error) {
                                                if (error.status === 422) {
                                                    $errors = error.responseJSON; //this will get the errors response data.
                                                    //show them somewhere in the markup
                                                    //e.g
                                                    var errorsHtml = '<div id="alert_div" class="alert alert-danger col-md-12 col-sm-12 col-xs-12" style="text-align:right;padding-right:10%;margin-bottom:-4%" role="alert"><ul>';
//
                                                    $.each($errors, function (key, value) {
                                                        errorsHtml += '<li>' + value[0] + '</li>'; //showing only the first error.
                                                    });
                                                    errorsHtml += '</ul></div>';
                                                    $('fieldset').append(errorsHtml);
                                                    swal
                                                    ({
                                                        title: 'خطاهای زیر را برطرف کنید !',
                                                        text: '',
                                                        type:'error',
                                                        confirmButtonText: "بستن"
                                                    });
                                                } else if (error.status === 500) {
                                                    swal
                                                    ({
                                                        title: 'لطفا با بخش پشتیبانی تماس بگیرید',
                                                        text: 'خطایی رخ داده است',
                                                        type:'error',
                                                        confirmButtonText: "بستن"
                                                    });
                                                    console.log(error);
                                                }
                                            }
                                        });
                                    } else {
                                        swal
                                        ({
                                            title: 'منصرف شدید',
                                            text: 'درخواست ثبت نشد',
                                            type:'error',
                                            confirmButtonText: "بستن"
                                        });
                                    }
                                });

                        }


                });

            }else
                {
                    swal
                    ({
                        title: 'لطفا سطری را انتخاب نمایید',
                        text: '',
                        type:'',
                        confirmButtonText: "بستن"
                    });
                    return false;
                }
        });



        $(document).on('click','#use_certificate',function(){
            var recordId  = '';
            var newRate   = '';
            var newPrice  = '';
            var newCount  = '';
            var unitCount = '';
            var token = $('#token').val();
            var requestId = $('#requestId').val();
            var certificateType = $(this).attr('content');
            $("[name = 'record']:checked").each(function(){
                recordId  += $(this).val()+',';
                newRate   += $(this).parents('tr').find('.new_rate').val()+',';
                newCount  += $(this).parents('tr').find('.new_count').val()+',';
                unitCount += $(this).parents('tr').find('.unit_count').val()+',';
                newPrice += $(this).parents('tr').find('.new_price').val().replace(/,/g , '')+',';
            });

            var receiverId = '';
            $("[name='receiverId']:selected").each(function(){
                receiverId += $(this).val();
                $('#receiverId').val(receiverId);
            });
            var shop_comp = $('#shop_comp').val();
            if(shop_comp == '' || shop_comp == null)
            {
                $('#shop_comp').css('border' , 'red 4px solid');
                $('#shop_comp').focus();
                return false;
            }
            if ($('input.record_ch').is(':checked'))
            {
                $("[name = 'record']:checked").each(function(){
                    var td    = $(this);
                    var rate  = $(this).parents('tr').find('.new_rate').val();
                    var price = $(this).parents('tr').find('.new_price').val();
                    var count = $(this).parents('tr').find('.new_count').val();
                    if(count == '' || count == null )
                    {
                        $(this).parents('tr').find('.new_count').css('border' , 'red 4px solid' );
                        $(this).parents('tr').find('.new_count').focus();
                        return false;
                    }
                    else if(rate == '' || rate == null)
                    {
                        $(this).parents('tr').find('.new_rate').css('border' , 'red 4px solid' );
                        $(this).parents('tr').find('.new_rate').focus();
                        return false;
                    }
                    else if(price == '' || price == null )
                    {
                        $(this).parents('tr').find('.new_price').css('border' , 'red 4px solid' );
                        $(this).parents('tr').find('.new_price').focus();
                        return false;
                    }
                    else
                    {

                        swal({
                                    title: "آیا از ثبت درخواست مطمئن هستید؟",
                                    text: "",
                                    type: "warning",
                                    showCancelButton: true,
                                    confirmButtonColor: "	#5cb85c",
                                    cancelButtonText: "خیر ، منصرف شدم",
                                    confirmButtonText: "بله ثبت شود",
                                    closeOnConfirm: true,
                                    closeOnCancel: false
                                },
                                function (isConfirm) {
                                    if (isConfirm) {
                                        //serialize() send all form input values
                                        var formData = $('#form_certificate').serialize();
                                        //                    console.log(formData);
                                        //                    return false;
//                                        $.ajaxSetup({
//                                            headers: {
//                                                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
//                                            }
//                                        });
                                        $.ajax({
                                            url: "{{ url('admin/execute_certificate') }}",
                                            type: 'POST',

                                            //dataType: 'json',
                                            data:
                                                {
                                                    'recordId': recordId,
                                                    'newRate' : newRate ,
                                                    'newPrice' : newPrice,
                                                    'newCount':newCount,
                                                    '_token' : token,
                                                    'unitCount':unitCount,
                                                    'receiverId':receiverId,
                                                    'certificateType' : certificateType,
                                                    'shop_comp'       : shop_comp,
                                                    'requestId'      : requestId
                                                },
                                            //context : td,
                                            success: function (response) {
                                               // $(td).parentsUntil(DOM,'tr').hide();
                                                console.log(response);
                                                swal
                                                ({
                                                    title: 'گواهی ثبت شد',
                                                    text:'گواهی به لیست گواهی ها اضافه شد',
                                                    type:'success',
                                                    confirmButtonText: "بستن"
                                                });
                                                setInterval(function(){ window.location.reload(true); }, 1000);
                                            },
                                            error: function (error) {
                                                if (error.status === 422) {
                                                    $errors = error.responseJSON; //this will get the errors response data.
                                                    //show them somewhere in the markup
                                                    //e.g
                                                    var errorsHtml = '<div id="alert_div" class="alert alert-danger col-md-12 col-sm-12 col-xs-12" style="text-align:right;padding-right:10%;margin-bottom:-4%" role="alert"><ul>';
//
                                                    $.each($errors, function (key, value) {
                                                        errorsHtml += '<li>' + value[0] + '</li>'; //showing only the first error.
                                                    });
                                                    errorsHtml += '</ul></div>';
                                                    $('fieldset').append(errorsHtml);
                                                    swal
                                                    ({
                                                        title: 'خطاهای زیر را برطرف کنید !',
                                                        text: '',
                                                        type:'error',
                                                        confirmButtonText: "بستن"
                                                    });
                                                } else if (error.status === 500) {
                                                    swal
                                                    ({
                                                        title: 'لطفا با بخش پشتیبانی تماس بگیرید',
                                                        text: 'خطایی رخ داده است',
                                                        type:'error',
                                                        confirmButtonText: "بستن"
                                                    });
                                                    console.log(error);
                                                }
                                            }
                                        });
                                    } else {
                                        swal
                                        ({
                                            title: 'منصرف شدید',
                                            text: 'درخواست ثبت نشد',
                                            type:'error',
                                            confirmButtonText: "بستن"
                                        });
                                    }
                                });

                    }


                });

            }else
            {
                swal
                ({
                    title: 'لطفا سطری را انتخاب نمایید',
                    text: '',
                    type:'',
                    confirmButtonText: "بستن"
                });
                return false;
            }
        });


        $(document).on('click','#service_certificate',function(){
            var recordId  = '';
            var newRate   = '';
            var newPrice  = '';
            var newCount  = '';
            var unitCount = '';
            var token = $('#token').val();
            var requestId = $('#requestId').val();
            var certificateType = $(this).attr('content');
            $("[name = 'record']:checked").each(function(){
                recordId  += $(this).val()+',';
                newRate   += $(this).parents('tr').find('.new_rate').val()+',';
                newCount  += $(this).parents('tr').find('.new_count').val()+',';
                unitCount += $(this).parents('tr').find('.unit_count').val()+',';
                newPrice += $(this).parents('tr').find('.new_price').val().replace(/,/g , '')+',';
            });

            var receiverId = '';
            $("[name='receiverId']:selected").each(function(){
                receiverId += $(this).val();
                $('#receiverId').val(receiverId);
            });

            var shop_comp = $('#shop_comp').val();
            if(shop_comp == '' || shop_comp == null)
            {
                $('#shop_comp').css('border' , 'red 4px solid');
                $('#shop_comp').focus();
                return false;
            }
            if ($('input.record_ch').is(':checked'))
            {
                $("[name = 'record']:checked").each(function(){
                    var td    = $(this);
                    var rate  = $(this).parents('tr').find('.new_rate').val();
                    var price = $(this).parents('tr').find('.new_price').val();
                    var count = $(this).parents('tr').find('.new_count').val();
                    if(count == '' || count == null )
                    {
                        $(this).parents('tr').find('.new_count').css('border' , 'red 4px solid' );
                        $(this).parents('tr').find('.new_count').focus();
                        return false;
                    }
                    else if(rate == '' || rate == null)
                    {
                        $(this).parents('tr').find('.new_rate').css('border' , 'red 4px solid' );
                        $(this).parents('tr').find('.new_rate').focus();
                        return false;
                    }
                    else if(price == '' || price == null )
                    {
                        $(this).parents('tr').find('.new_price').css('border' , 'red 4px solid' );
                        $(this).parents('tr').find('.new_price').focus();
                        return false;
                    }
                    else
                    {

                        swal({
                                    title: "آیا از ثبت درخواست مطمئن هستید؟",
                                    text: "",
                                    type: "warning",
                                    showCancelButton: true,
                                    confirmButtonColor: "	#5cb85c",
                                    cancelButtonText: "خیر ، منصرف شدم",
                                    confirmButtonText: "بله ثبت شود",
                                    closeOnConfirm: true,
                                    closeOnCancel: false
                                },
                                function (isConfirm) {
                                    if (isConfirm) {
                                        //serialize() send all form input values
//                                        var formData = $('#form_certificate').serialize();
//                                        //                    console.log(formData);
//                                        //                    return false;
//                                        $.ajaxSetup({
//                                            headers: {
//                                                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
//                                            }
//                                        });
                                        $.ajax({
                                            url: "{{ url('admin/execute_certificate') }}",
                                            type: 'POST',
                                            //dataType: 'json',
                                            data:
                                                {
                                                    'recordId': recordId,
                                                    'newRate' : newRate ,
                                                    'newPrice' : newPrice,
                                                    'newCount':newCount,
                                                    '_token' : token,
                                                    'unitCount':unitCount,
                                                    'receiverId':receiverId,
                                                    'certificateType' : certificateType,
                                                    'shop_comp'       : shop_comp,
                                                    'requestId'      : requestId
                                                },

                                            //context : td,
                                            success: function (response) {
                                                //$(td).parentsUntil(DOM,'tr').hide();
                                                console.log(response);
                                                swal
                                                ({
                                                    title: 'گواهی ثبت شد',
                                                    text:'گواهی به لیست گواهی ها اضافه شد',
                                                    type:'success',
                                                    confirmButtonText: "بستن"
                                                });
                                                setInterval(function(){ window.location.reload(true) }, 1000);
                                            },
                                            error: function (error) {
                                                if (error.status === 422) {
                                                    $errors = error.responseJSON; //this will get the errors response data.
                                                    //show them somewhere in the markup
                                                    //e.g
                                                    var errorsHtml = '<div id="alert_div" class="alert alert-danger col-md-12 col-sm-12 col-xs-12" style="text-align:right;padding-right:10%;margin-bottom:-4%" role="alert"><ul>';
//
                                                    $.each($errors, function (key, value) {
                                                        errorsHtml += '<li>' + value[0] + '</li>'; //showing only the first error.
                                                    });
                                                    errorsHtml += '</ul></div>';
                                                    $('fieldset').append(errorsHtml);
                                                    swal
                                                    ({
                                                        title: 'خطاهای زیر را برطرف کنید !',
                                                        text: '',
                                                        type:'error',
                                                        confirmButtonText: "بستن"
                                                    });
                                                } else if (error.status === 500) {
                                                    swal
                                                    ({
                                                        title: 'لطفا با بخش پشتیبانی تماس بگیرید',
                                                        text: 'خطایی رخ داده است',
                                                        type:'error',
                                                        confirmButtonText: "بستن"
                                                    });
                                                    console.log(error);
                                                }
                                            }
                                        });
                                    } else {
                                        swal
                                        ({
                                            title: 'منصرف شدید',
                                            text: 'درخواست ثبت نشد',
                                            type:'error',
                                            confirmButtonText: "بستن"
                                        });
                                    }
                                });
                    }
                });

            }else
            {
                swal
                ({
                    title: 'لطفا سطری را انتخاب نمایید',
                    text: '',
                    type:'',
                    confirmButtonText: "بستن"
                });
                return false;
            }
        });
    </script>
@endsection
