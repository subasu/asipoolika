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
                                   class="control-label pull-right col-md-12 col-sm-12 col-xs-12 form-group"> مشخصات
                                صاحب کارت کارگری
                            </label>
                            {{--<div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback pull-right">--}}
                            <div class="col-md-6 col-sm-6 col-xs-12 form-group pull-right">
                                <input type="text" class="form-control" style="text-align:right;" id="name"
                                       name="name" placeholder="نام" min="1" max="5">
                            </div>
                            {{--</div>--}}
                            <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                                <input type="text" class="form-control" style="text-align:right;" id="family"
                                       name="family" placeholder="نام خانوادگی" min="1" max="5" >
                            </div>

                            <div class="">
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
                                            <span class="image-preview-input-title2" id="pic">انتخاب کارت کارگری</span>
                                            <input type="file" id="pic"  accept="image/png, image/jpeg, image/gif" name="image" />
                                            <!-- rename it -->
                                        </div>
                                        </span>
                                    </div><!-- /input-group image-preview [TO HERE]-->

                                    <div class="col-md-6 col-sm-6 col-xs-12 form-group pull-right">
                                        <input type="text" class="form-control" style="text-align:right;" id="date"
                                               name="date" placeholder="تاریخ">
                                    </div>

                                       <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                                           <button id="addWorkerCard" type="button" class="btn btn-success col-md-12"> ثبت کارت
                                           </button>
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
        $(document).on('click','#addWorkerCard',function(){
           // alert('hello');
            var name   = $('#name').val();
            var family = $('#family').val();
            var date   = $('#date').val();
            var pic    = $('#pic').val();

            var formData = new FormData($('#dealForm')[0]);

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });

                    $.ajax
                    ({
                        cache:false,
                        url  : "{{Url('admin/addWorkerCard')}}",
                        type : 'POST',
                        processData :false,
                        contentType: false,
                        data : formData,
                        beforeSend:function()
                        {
                            if(name == '' || name == null)
                            {
                                $('#name').focus();
                                $('#name').css('border-color' , 'red');
                                return false;
                            }
                            if(family == '' || family == null)
                            {
                                $('#family').focus();
                                $('#family').css('border-color' , 'red');
                                return false;
                            }
                            if(date == '' || date == null)
                            {
                                $('#date').focus();
                                $('#date').css('border-color' , 'red');
                                return false;
                            }

//                            if(pic == '' || pic == null)
//                            {
//                                $('#pic').focus();
//                                $('#pic').css('border-color' , 'red');
//                                return false;
//                            }
                        },
                        success:function(response)
                        {
//                            swal({
//                                title: "",
//                                text: response,
//                                type: "info",
//                                confirmButtonText: "بستن"
//                            });
                            window.location.href = "workerCardManage";
                        },error:function(error)
                        {
                            swal({
                                title: "",
                                text: 'خطایی رخ داده است.لطفا با بخش پشتیبانی تماس بگیرید',
                                type: "warning",
                                confirmButtonText: "بستن"
                            });
                            console.log(error);
                        }
                    })



        });
    </script>

@endsection