@extends('layouts.adminLayout')
@section('content')

    <input type="hidden" value="{{$user_id=\Illuminate\Support\Facades\Auth::user()->id}}">
    <div class="page-title">
        <div class="title_right">
            <h3>
                ثبت درخواست های خدمت بایگانی شده
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
                        <div class="row"  style="font-size: 18px;margin-top:15px;direction: rtl;margin-bottom: 15px;">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="col-md-3 col-sm-5 col-xs-12 pull-right">
                                    <label for="unit_id"><span class="required" style="font-size: 20px;color: red;">*</span> واحد درخواست دهنده :</label>
                                    <select class="form-control" name="unit_id" id="unit_id"  style="color:black">
                                        <option value="">واحد را انتخاب کنید</option>
                                        @foreach($units as $item)
                                            <option value="{{$item->id}}">
                                                {{$item->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 col-sm-5 col-xs-12 pull-right">
                                    <label for="unit_user"><span class="required" style="font-size: 20px;color: red;">*</span> کاربر درخواست دهنده :</label>
                                    <select class="form-control" name="unit_user" style="color: black" id="unit_user">
                                        <option>-</option>
                                    </select>
                                </div>
                                <div class="col-md-3 col-sm-5 col-xs-12 pull-right">
                                    <label for="supplier_id"><span class="required" style="font-size: 20px;color: red;">*</span> کارپرداز :</label>
                                    <select class="form-control" name="supplier_id" id="supplier_id"  style="color:black">
                                        <option value="">کارپرداز را انتخاب کنید</option>
                                        @foreach($suppliers as $item)
                                            <option value="{{$item->id}}">
                                                {{$item->name}} {{$item->family}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 col-sm-5 col-xs-12 pull-right">
                                    <label for="unit_user"><span class="required" style="font-size: 20px;color: red;">*</span> تاریخ ثبت درخواست</label>
                                    <input type="text" name="" id="" class="form-control">
                                </div>
                                {{--<div class="col-md-3 col-sm-5 col-xs-12 pull-right">--}}
                                {{--<label for="receiver_id"><span class="required" style="font-size: 20px;color: red;">*</span> تحویل گیرنده :</label>--}}
                                {{--<select class="form-control" name="receiver_id" style="color: black" id="receiver_id">--}}
                                {{--<option>-</option>--}}
                                {{--</select>--}}
                                {{--</div>--}}
                            </div>
                        </div>
                        {{--<input type="hidden" value="3" name="request_type_id" id="request_type_id">--}}
                        <table class="table table-bordered mytable" dir="rtl" >
                            <thead>
                            <tr>
                                {{--<th>کد کالا</th>--}}
                                <th>عنوان خدمت</th>
                                <th>تعداد / مقدار</th>
                                <th style="text-align: center ;">نرخ</th>
                                <th style="text-align: center ;">قیمت</th>
                                <th style="border-right: 1px solid #d6d6c2">توضیحات</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr id="addingTableTr">
                                {{--<td>--}}
                                {{--<input id="name" class="form-control col-md-7 col-xs-12" name="name"--}}
                                {{--placeholder="" required="required" type="text">--}}
                                {{--</td>--}}
                                <td class="col-md-2">
                                    <input id="product_title" class="form-control req" name="product_title"
                                           placeholder="نام کالا مورد نیاز" required="required" type="text"></td>
                                <td class="col-md-1">
                                    <input id="product_count" class="form-control req count" name="product_count"
                                           placeholder="" required="required" type="text" onkeypress='return event.charCode >= 48 && event.charCode <= 57' maxlength="1000">
                                </td>

                                <td class="col-md-2"><input type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57" class="form-control rate" id="rate"  name="product_rate"/></td>
                                <td class="col-md-2"><input type="text" readonly class="form-control price" id="price" content="content" name="product_price" style="font-size:16px;color:red"/></td>
                                <td class="col-md-4" style="border-right: 1px solid #d6d6c2">
                                    <input id="product_details" class="form-control" name="product_details"
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
                {{--<div class="x_title">--}}
                {{--<h2> درخواست نهایی--}}
                {{--</h2>--}}
                {{--<ul class="nav navbar-right panel_toolbox">--}}
                {{--<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>--}}
                {{--</li>--}}
                {{--<li><a class="close-link"><i class="fa fa-close"></i></a>--}}
                {{--</li>--}}
                {{--</ul>--}}
                {{--<div class="clearfix"></div>--}}
                {{--</div>--}}
                <div class="col-md-12 col-sm-8 col-xs-12">
                    <div class="x_content">
                        <form class="form-horizontal form-label-left product" novalidate id="product">
                            {!! csrf_field() !!}
                            <input type="hidden" value="{{$user_id}}" name="user_id" id="user_id">
                            <input type="hidden" value="0" name="record_count" id="record_count">
                            <input type="hidden" value="" name="supplier_id2" id="supplier_id2">
                            <input type="hidden" value="" name="unit_id2" id="unit_id2">
                            {{--request owner id : --}}
                            <input type="hidden" value="" name="user_id2" id="user_id2">

                            <table class="table table-bordered mytable" dir="rtl">
                                <thead>
                                <tr>
                                    {{--<th class="col-md-1">ردیف</th>--}}
                                    {{--<th>کد کالا</th>--}}
                                    <th class="col-md-2">عنوان خدمت</th>
                                    <th class="col-md-1">تعداد / مقدار</th>
                                    <th class="col-md-1" style="text-align: center ;">نرخ</th>
                                    <th class="col-md-1" style="text-align: center ;">قیمت</th>
                                    <th class="col-md-2">تحویل گیرنده</th>
                                    <th class="col-md-1">توضیحات</th>
                                    <th class="col-md-1" style="border-right: 1px solid #d6d6c2">حذف</th>
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

        <script>
            $('#unit_id').change(function() {
                var $this = $(this);
                var id = $this.val();
                var receiver_id=$('#receiver_id').val();

                $.ajax({
                    url: "{{ url('units') }}",
                    type: 'GET',
                    dataType: 'json',
                    data: {unit_id:id},
                    success: function(response) {
                        var html;
                        html += '<option value="">کاربر را انتخاب کنید</option>';
                        $.each(response.users, function (index, value) {
                            html += '<option value="' + value['id'] + '">' + value['name'] + ' ' + value['family'] + '</option>';
                        });
                        $("#unit_user").html(html);

                        var html2;
                        html2 += '<option value="">تحویل گیرنده را انتخاب کنید</option>';
                        $.each(response.receiver_id, function (index, value) {
                            html2 += '<option value="' + value['id'] + '">' + value['name'] + ' ' + value['family'] +' ( '+value['unit_name']+' ) '+ '</option>';
                        });
                        $("#receiver_id").html(html2);

                    },
                    error: function(error) {
                        var errors = error.responseJSON;
                        console.log(errors);
                    }
                });
            });



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
            $('#product_title').keydown(function(){ $('#product_title').css('border-color','#ccc');});
            $('#product_count').keydown(function(){ $('#product_count').css('border-color','#ccc');});
            $('#add_to_list').click(function(){
                $('#supplier_id2').val($('#supplier_id').val());
                $('#unit_id2').val($('#unit_id').val());
                //request owner
                $('#user_id2').val($('#unit_user').val());
                $('#receiver_id2').val($('#receiver_id').val());
                count++;
                var row_id='row'+count;
                var select_id='select'+count;
                var product_title = $('#product_title').val();
                var product_count = $('#product_count').val();

                if(product_title == '' || product_title == null)
                {
                    $('#product_title').focus();
                    $('#product_title').css('border-color','red');
                    return false;
                }

                else if(product_count == '' || product_count == null)
                {
                    $('#product_count').focus();
                    $('#product_count').css('border-color','red');
                    return false;
                }
                else if(product_title != '' && product_title!= null && product_count!= '' && product_count!= null)
                {
                    var row='<tr id="'+row_id+'">'+
//                        '<th scope="row">'+count+'</th>'+
                            '<td>'+'<input style="padding-right:5px;" class="required form-control" type="text" name="product_title[]" value="'+$('#product_title').val()+'">'+'</td>'+
                            '<td>'+'<input style="padding-right:5px;" class="required form-control" type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57" maxlength="1000" name="product_count[]" value="'+$('#product_count').val()+'">'+'</td>'+
//                            '<td>'+$.trim($("#unit_count option:selected").text())+'</td>'+
//                            '<input type="text" name="unit_count[]" value="'+$.trim($("#unit_count option:selected").text())+'">'+
                            '<td>'+'<input style="padding-right:5px;" class="required form-control" type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57" name="product_rate[]" value="'+$('#rate').val()+'">'+'</td>'+
                            '<td>'+'<input style="padding-right:5px;" class="required form-control" type="text" readonly name="product_price[]" value="'+$('#price').val().replace(/,/g , '')+'">'+'</td>'+
                            '<td>'+'<select id="'+select_id+'" class="form-control" name="product_receiver[]">'+
                            receivers2(select_id)
                            +'</select>'+
                            '</td>'+
                            '</td>'+
                            '<td class="col-md-4">'+
                            '<input id="product_details" class="form-control" name="product_details[]" placeholder=""  value="'+$('#product_details').val()+'" type="text" >'+'</td>'+
                            '<input type="hidden" value="'+$.trim($("#unit_count option:selected").text())+'" name="unit_count_each[]">'+
//                        '<input type="hidden" value="'+$.trim($("#unit_count option:selected").text())+'" name="unit_count_each[]">'+
                            '<td style="border-right: 1px solid #d6d6c2">'+
                            '<a type="button" class="btn btn-danger remove_row" data-toggle="tooltip" title="حذف" style="font-size:18px;">'+
                            '<span class="fa fa-trash"></span>'+
                            '</a>'+
                            '</td>'+
                            '</tr>';
                    $('#table-row').append(row);
                    record_count++;
                    $('#record_count').val(record_count);
                    //rayat:make inputs empty
                    $("#addingTableTr").children("td").children("input").each(function(){
                        $(this).val('');
                    });
                    return false;
                    //rayat:make inputs empty end...
                }
            });
            $(document).on('click','.remove_row', function(){
                $(this).closest('tr').remove();
                record_count--;
                $('#record_count').val(record_count);
            });
        </script>
        <script>
            {{--function unit_count_each_record(select_id) {--}}
            function receivers2()
            {
                var unit_id=$('#unit_id').val();
                $.ajax({
                    url: "{{ url('/receiver2') }}",
                    type: 'GET',
                    dataType: 'json',
                    data: {id:unit_id},
                    success: function (response) {
                        var html;
                        $.each(response.receivers, function (index, value) {
                            html += '<option value="' + value['id'] + '">' + value['name'] +' '+value['family']+' ( '+value['unit_name']+' ) '+ '</option>';
                        });
                        var select_id='select'+count;
                        $("#"+select_id).html(html);
                    },
                    error: function (error) {
                        var errors = error.responseJSON;
                        console.log(errors);
                    }
                });
            }
        </script>
        <script>
            $('#save_request').click(function () {
                var table = document.getElementById("table-row");
                var rows = table.getElementsByTagName("tr");
                if (rows.length) {
                    swal({
                                title: "توجه کنید!",
                                text: "آیا از ثبت درخواست مطمئن هستید؟",
                                type: "",
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
                                    var formData = $('#product').serialize();
                                    console.log(formData);
//                                return false;
                                    $.ajaxSetup({
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                                        }
                                    });
                                    $.ajax({
                                        url: "{{ url('special/serviceRequest') }}",
                                        type: 'POST',
                                        dataType: 'json',
                                        data: formData,
                                        success: function (response) {
                                            swal
                                            ({
                                                title: 'درخواست ثبت شد',
                                                text:'درخواست به لیست درخواست های شما اضافه شد',
                                                type:'success',
                                                confirmButtonText: "بستن"
                                            });
                                            //rayat: refresh page after showing alert
                                            setTimeout (myTimer, 1500);
                                            function myTimer() {
                                                location.reload()
                                            }
                                            //rayat: refresh page after showing alert END ...
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
                }else
                    swal({
                        title: "توجه کنید!",
                        text: "شما هنوز درخواستی ثبت نکرده اید",
                        type: "",
                        confirmButtonText: "بستن"
                    });
            });
        </script>
        <script>
            function formatNumber (num) {
                return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
            }
        </script>
        <script>
            $('.rate').on('keyup', function() {
                var rate=$(this).parents('tr').find('.rate').val();
                var count=$(this).parents('tr').find('.count').val();
                var price = rate * count;
                $(this).parents('tr').find('.price').val(formatNumber(price));
                price = price.replace(/,/g , '');
            });

        </script>
@endsection
