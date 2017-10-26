@extends('layouts.adminLayout')
@section('content')
    <!-- page content -->
    <div class="" role="main">
        <div class="">
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-8 col-sm-12 col-xs-12 col-md-offset-2">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2></h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link" data-toggle="tooltip" title="جمع کردن"><i
                                                class="fa fa-chevron-up"></i></a>
                                </li>
                                <li><a class="close-link" data-toggle="tooltip" title="بستن"><i class="fa fa-close"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <br/>
                            <form class="form-horizontal form-label-right input_mask" enctype="multipart/form-data" id="dealForm" style="direction: rtl;">
                                {{ csrf_field() }}
                                <input type="hidden" id="token" name="csrf-token" value="{{ csrf_token() }}">
                                <input type="hidden" name="dealTypeName" id="dealTypeName" value="">
                                <input type="hidden" name="dealTypeEn" id="dealTypeEn" value="">
                                <input type="hidden" name="property_id" id="property_id" value="">
                                <div class="row">
                                    <div class="col-md-12" style="">
                                    </div>
                                </div>

                                <label style="font-size: 20px;margin-bottom: 10px;"
                                       class="control-label pull-right col-md-12 col-sm-12 col-xs-12 form-group">
                                    آپلود فاکتور
                                </label>
                                {{--<div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback pull-right">--}}


                                <div class="col-md-6 col-sm-6 col-xs-12 form-group pull-right">
                                    <lable style="font-size: 120%;">شماره فاکتور:</lable>
                                    <input type="number" class="form-control" style="text-align:right;" id="factorNumber"
                                           name="factorNumber" placeholder="شماره فاکتور" >
                                </div>

                                <div class="">
                                    <div class="col-md-12"><lable style="font-size: 120%;">توضیحات : حجم فایل فاکتور نباید بیش از 150 کیلو بایت باشد ، پسوند فایل فاکتور  باید از نوع  png یا jpg  باشد.</lable></div>
                                    <div class="input-group image-preview col-md-12" style="padding:0px 10px !important;">
                                        <input type="text" class="form-control image-preview-filename" disabled="disabled">
                                        <!-- don't give a name === doesn't send on POST/GET -->
                                        <span class="input-group-btn">
                                        <!-- image-preview-clear button -->
                                        <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
                                            <span class="glyphicon glyphicon-remove"></span> پاک کردن
                                        </button>
                                            <!-- image-preview-input -->
                                        <div class="btn btn-default image-preview-input ">
                                            <span class="glyphicon glyphicon-folder-open"></span>
                                            <span class="image-preview-input-title2" id="pic">انتخاب فایل فاکتور</span>
                                            <input type="file" id="image"   name="image" />
                                            <!-- rename it -->
                                        </div>
                                        </span>
                                    </div><!-- /input-group image-preview [TO HERE]-->

                                    <div class="col-md-6 col-sm-6 col-xs-12 form-group pull-right">
                                        <input type="text" class="form-control" style="text-align:right;" id="date"
                                               name="date" placeholder="تاریخ">
                                    </div>

                                    <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                                        <button id="addBill" type="button" class="btn btn-success col-md-12"> ثبت فاکتور
                                        </button>
                                        <input type="hidden" id="requestId" name="requestId" value="{{$id}}">
                                    </div>

                                </div>
                            </form>


                        </div>
                    </div>
                </div>
            </div>
        </div>




        <script src="{{URL::asset('public/js/persianDatepicker.js')}}"></script>
        <script>
            $('#date').persianDatepicker();
        </script>


        <script>
            $(document).on('click','#addBill',function(){
                // alert('hello');

                var factorNumber = $('#factorNumber').val();
                var date   = $('#date').val();
                var image    = $('#image').val();
                var formData = new FormData($('#dealForm')[0]);


                $.ajax
                ({
                    cache:false,
                    url  : "{{Url('admin/saveBill')}}",
                    type : 'POST',
                    processData :false,
                    contentType: false,
                    data : formData,
                    beforeSend:function()
                    {

                        if(factorNumber == '' || factorNumber == null)
                        {
                            $('#factorNumber').focus();
                            $('#factorNumber').css('border-color' , 'red');
                            return false;
                        }
                        if(date == '' || date == null)
                        {
                            $('#date').focus();
                            $('#date').css('border-color' , 'red');
                            return false;
                        }


                    },
                    success:function(response)
                    {
                        swal({
                            title: "",
                            text: response,
                            type: "info",
                            confirmButtonText: "بستن"
                        });
                        // window.location.href = "workerCardManage";
                    },error:function(error)
                    {
                        if (error.status === 422) {
                            var x = error.responseJSON;
                            var errorsHtml = '';
                            var count = 0;
                            $.each(x, function (key, value) {
                                errorsHtml += value[0] + '\n'; //showing only the first error.
                            });
                            console.log(count)
                            swal({
                                title: "",
                                text: errorsHtml,
                                type: "info",
                                confirmButtonText: "بستن"
                            });
                        }
                        if(error.status === 500)
                        {
                            swal({
                                title: "",
                                text: 'خطایی رخ داده است.لطفا با بخش پشتیبانی تماس بگیرید',
                                type: "warning",
                                confirmButtonText: "بستن"
                            });
                            console.log(error);
                        }

                    }
                })



            });
        </script>

@endsection