@extends('layouts.adminLayout')
@section('content')
    <input type="hidden" value="{{$user_id=\Illuminate\Support\Facades\Auth::user()->id}}">
    <div class="page-title">
        <div class="title_right">
            <h3>
                درخواست خرید کالا
            </h3>
        </div>
    </div>
    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2> فرم شماره 1
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

                <div class="col-md-12 col-sm-8 col-xs-12">
                    <div class="x_content">
                        {{--<input type="hidden" value="3" name="request_type_id" id="request_type_id">--}}
                        <table class="table table-bordered mytable" dir="rtl">
                            <thead>
                            <tr>
                                {{--<th>کد کالا</th>--}}
                                <th>عنوان خدمت</th>
                                <th>تعداد / مقدار</th>
                                <th>توضیحات</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="col-md-4">
                                    <input id="service_title" class="form-control req" name="product_title"
                                           placeholder="" required="required" type="text"></td>
                                <td class="col-md-2">
                                    <input id="service_count" class="form-control req" name="product_count"
                                           placeholder="" required="required" type="number" min="0">
                                </td>
                                <td class="col-md-8">
                                    <input id="service_details" class="form-control" name="service_details"
                                           placeholder="" required="required" type="text" >
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <div class="col-md-8">
                                <button id="add_to_list" type="button"
                                        class="btn btn-primary col-md-6 col-md-offset-6"> به لیست اضافه شود
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-md-2 col-sm-2 col-xs-12"></div>
            </div>
            <div class="x_panel">
                <div class="x_title">
                    <h2> درخواست نهایی
                    </h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="col-md-12 col-sm-8 col-xs-12">
                    <div class="x_content">
                        <form class="form-horizontal form-label-left product" novalidate id="service">
                            {!! csrf_field() !!}
                            <input type="hidden" value="{{$user_id}}" name="user_id" id="user_id">
                            <input type="hidden" value="0" name="record_count" id="record_count">

                            <table class="table table-bordered mytable" dir="rtl">
                                <thead>
                                <tr>
                                    <th class="col-md-1">ردیف</th>
                                    {{--<th>کد کالا</th>--}}
                                    <th class="col-md-2">عنوان کالا</th>
                                    <th class="col-md-1">تعداد / مقدار</th>
                                    <th class="col-md-4">توضیحات</th>
                                    <th class="col-md-1">حذف</th>
                                </tr>
                                </thead>
                                <tbody id="table-row">

                                </tbody>
                            </table>
                        </form>
                        <div class="form-group">
                            <div class="col-md-8">
                                <button id="save_request" name="save_request" type="button" class="btn btn-success col-md-6 col-md-offset-6"> ثبت نهایی
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                {{--! end tables --}}
            </div>
        </div>

        {{--kianfar--}}

        <script>
            $(document).ready(function(){
                $.ajax({
                    url: "{{ Url('/unit_count') }}",
                    type: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        console.log(response);
                        var html;
                        $.each(response.unit_counts, function (index, value) {
                            html += '<option value="' + value + '">' + value['title'] + '</option>';
                        });
                        $("#unit_count").html(html);
                    },
                    error: function (error) {
                        //var errors = error.responseJSON;
                        console.log(error);
                    }
                });
            });

        </script>

        <script>
            var count=0;
            var record_count=0;
            $('#add_to_list').click(function(){
                count++;
                var row_id='row'+count;
                var select_id='select'+count;
                var service_title = $('#service_title').val();
                var service_count = $('#service_count').val();

                if(service_title == '' || service_title == null)
                {
                    $('#service_title').focus();
                    $('#service_title').css('border-color','red');
                    return false;
                }

                else if(service_count == '' || service_count == null)
                {
                    $('#service_count').focus();
                    $('#service_count').css('border-color','red');
                    return false;
                }
                else if(service_title != '' && service_title!= null && service_count!= '' && service_count!= null)
                {
                    var row='<tr id="'+row_id+'">'+
                            '<th scope="row">'+count+'</th>'+
                            '<td>'+'<input  style="padding-right:5px;" class="required form-control" type="text" name="service_title[]" value="'+$('#service_title').val()+'">'+'</td>'+
                            '<td>'+'<input style="padding-right:5px;" class="required form-control" type="number" name="service_count[]" value="'+$('#service_count').val()+'">'+'</td>'+
                            '<td class="col-md-9">'+
                            '<input id="service_details" class="form-control" name="service_details[]" placeholder=""  value="'+$('#service_details').val()+'" type="text" >'+'</td>'+
                                //'<td>'+'<select id="'+select_id+'" class="form-control">'+
                                //      unit_count_each_record(select_id)
                                //+'</select>'+
                                //'</td>'+
                            '<td>'+
                            '<a type="button" class="btn btn-danger remove_row" data-toggle="tooltip" title="حذف" style="font-size:18px;">'+
                            '<span class="fa fa-trash"></span>'+
                            '</a>'+
                            '</td>'+
                            '</tr>';
                    $('#table-row').append(row);
                    record_count++;
                    $('#record_count').val(record_count);
                }
            });
            $(document).on('click','.remove_row', function(){
                $(this).closest('tr').remove();
                record_count--;
                $('#record_count').val(record_count);
            });
        </script>

        <script>
            $('#save_request').click(function () {
                swal({
                            title: "آیا از ثبت درخواست مطمئن هستید؟",
                            text: "",
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "	#5cb85c",
                            cancelButtonText: "خیر ، منصرف شدم",
                            confirmButtonText: "بله ثبت شود",
                            closeOnConfirm: false,
                            closeOnCancel: false
                        },
                        function (isConfirm) {
                            if (isConfirm) {
                                //serialize() send all form input values
                                var formData = $('#service').serialize();
//                        console.log(formData);
                                $.ajaxSetup({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                                    }
                                });
                                $.ajax({
                                    url: "{{ url('user/serviceRequest') }}",
                                    type: 'POST',
                                    dataType: 'json',
                                    data: formData,
                                    beforeSend:function () {
                                        var counter = 0;
                                        $(".required").each(function() {
                                            if ($(this).val() === "") {
                                                $(this).css("border-color" , "red");
                                                counter++;
                                            }
                                        });
                                        if(counter > 0){
                                            swal
                                            ({
                                                title: '',
                                                text: 'تعدادی از فیلدهای فرم خالی است.لطفا فیلدها را پر نمایید سپس ثبت نهایی را بزنید',
                                                type:'warning',
                                                confirmButtonText: "بستن"
                                            });
                                            return false;
                                        }
                                    },
                                    success: function (response) {
                                        swal('درخواست ثبت شد', 'درخواست به لیست درخواست های شما اضافه شد', 'success');
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
