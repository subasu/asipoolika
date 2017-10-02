@extends('layouts.adminLayout')
@section('content')

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
                        <input type="hidden" value="{{$requestRecords[0]->request_id}}" name="request_id">
                    @endif
                    <div class="row" style="font-size: 18px;direction: rtl;text-align: right;margin-bottom: 10px;">
                        <div class="col-md-12"> بدینوسیله گواهی می شود خدمات انجام شده توسط شرکت / فروشگاه
                            <input id="shop_comp" name="shop_comp"
                                   placeholder="" required="required" type="text" style="width: 20%;padding:2px 5px 2px 5px;"> به واحد
                            <span style="color:red">{{$users[0]->unit->title}}</span> به آقای / خانم
                            <select name="receiver_id" id="" style="font-size: 18px;padding:2px 5px 2px 5px;">
                                @foreach($users as $user)
                                    <option value="{{$user->id}}">{{$user->name}} {{$user->family}}</option>
                                @endforeach
                            </select> تحویل گردید و پرداخت شده است.
                        </div>
                    </div>
                <table style="direction:rtl;text-align: center;font-size: 16px;" id="table" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
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
                    {{ csrf_field() }}
                    <input type="hidden" id="token" name="csrf-token" value="{{ csrf_token() }}">
                    <?php $row=1; ?>

                    @foreach($requestRecords as $requestRecord)
                        <tr>
                            <input type="hidden" value="{{$requestRecord->id}}" class="record_id" name="record_id[]">
                            <td style="text-align: center">
                                <input type="checkbox" value="{{$requestRecord->id}}" class=" record_ch" name="record">
                            </td>
                            <td>{{$row}}</td>
                            <?php $row++; ?>
                            <td>{{$requestRecord->title}}</td>
                            <input type="hidden" value="{{$requestRecord->title}}" id="record_title" class="record_title" name="">

                            <td class="gray3" id="count" content="{{$requestRecord->count}}">{{$requestRecord->count}} {{$requestRecord->unit_count}}</td>
                            <td class="gray3" ><input type="text" class="form-control new_count" id="new_count"  name="new_count[]"/></td>
                            <input type="hidden" class="record_count2" value="{{$requestRecord->count}}" name="count">
                            <input type="hidden" class="unit_count" value="{{$requestRecord->unit_count}}" name="unit_count">

                            <td class="gray2">{{number_format($requestRecord->rate)}} تومان</td>
                            <td class="gray2"><input type="text" class="form-control new_rate" id="new_rate"  name="new_rate[]"/></td>
                            <input type="hidden" value="{{$requestRecord->rate}}" id="record_rate" class="record_rate" name="">

                            <td class="gray1">{{number_format($requestRecord->price)}} تومان</td>
                            <td class="gray1"><input type="text" class="form-control new_price" id="new_price" content="content" name="new_price[]" style="font-size:16px;color:red"/></td>
                            <input type="hidden" value="{{$requestRecord->price}}" id="record_price" class="record_price" name="">
                            <input type="hidden" value="" id="new_price2" class="new_price2" name="new_price2[]">
                        </tr>
                    @endforeach

                    <input type="hidden" value="0" name="checked_count" id="checked_count">
                    <input type="hidden" value="" name="certificate_type" id="certificate_type">
                    </tbody>
                </table>

                </form>
                <div class="row">
                    <div class="col-md-12 col-md-offset-3">
                        <button id="use_certificate" content="2" class="btn btn-danger col-md-3">صدول گواهی تحویل و مصرف</button>
                        <button id="install_certificate"  content="1" class="btn btn-primary col-md-3">صدور گواهی تحویل و نصب</button>
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
            price = price.replace(',', '');
        });
    </script>
<script>

</script>
    <script>

var checked_count;
$(".record_ch").click(function() {
    if(this.checked) {
        checked_count=$('#checked_count').val();
        checked_count++;
        $('#checked_count').val(checked_count);
    }
    if(!(this.checked)) {
        checked_count=$('#checked_count').val();
        checked_count--;
        $('#checked_count').val(checked_count);
    }
});

        $('#install_certificate').click(function () {
            var certificate_type=$(this).attr('content');
            $('#certificate_type').val(certificate_type);

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
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{ url('admin/execute_certificate') }}",
                        type: 'POST',
                        dataType: 'json',
                        data: formData,
                        success: function (response) {
                            console.log(response);
                            swal('گواهی ثبت شد', 'گواهی به لیست گواهی ها اضافه شد', 'success');
                            location.reload(true);
//                                    window.location.href='';
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
                                swal("خطاهای زیر را برطرف کنید !", '', "error");
                            } else if (error.status === 500) {
                                swal('لطفا با بخش پشتیبانی تماس بگیرید', 'خطایی رخ داده است', 'success');
                                console.log(error);
                            }
                        }
                    });
                } else {
                    swal("منصرف شدید", "درخواست ثبت نشد", "error");
                }
            });
        });


    </script>

@endsection