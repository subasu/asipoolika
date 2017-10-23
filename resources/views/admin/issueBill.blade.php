@extends('layouts.adminLayout')
@section('content')
    <style>
        .padding-right-1px{padding-right: 1px !important;}
    </style>
    <!-- page content -->
    <div class="" role="main">
        <div class="">
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-8 col-sm-12 col-xs-12 col-md-offset-2">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>آپلود فاکتورهای درخواست شماره : {{$id}}</h2>
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
                                <input type="hidden" name="dealTypeName" id="dealTypeName" value="">
                                <label style="font-size: 20px;margin-bottom: 10px;"
                                       class="control-label pull-right col-md-12 col-sm-12 col-xs-12 form-group">
                                </label>
                                <div class="col-md-12">
                                    <div class="col-md-6 col-sm-6 col-xs-12 form-group pull-right">
                                        <label class="control-label col-md-5 col-sm-4 col-xs-12 padding-right-1px pull-right" for=""> شماره فاکتور :
                                        </label>
                                        <input type="number" min="1000" class="form-control" name="" id="">
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12 form-group pull-right">
                                        <label class="control-label col-md-5 col-sm-4 col-xs-12 padding-right-1px pull-right" for=""> تاریخ :
                                        </label>
                                        <input type="text" class="form-control" name="" id="">
                                    </div>
                                    <lable style="font-size: 16px;">توضیحات : حجم فایل امضا نباید بیش از 150 کیلو بایت باشد ، پسوند فایل امضا باید از نوع png باشد.</lable>
                                    <div class="input-group image-preview" style="margin-top: 10px;">
                                        <input type="text" class="form-control image-preview-filename" disabled="disabled">
                                        <!-- don't give a name === doesn't send on POST/GET -->
                                        <span class="input-group-btn">
                                    <!-- image-preview-clear button -->
                                    <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
                                        <span class="glyphicon glyphicon-remove"></span> پاک کردن
                                    </button>
                                            <!-- image-preview-input -->
                                    <div class="btn btn-default image-preview-input">
                                        <span class="glyphicon glyphicon-folder-open"></span>
                                        <span class="image-preview-input-title">انتخاب تصویر فاکتور</span>
                                        <input type="file" id="file" name="file" accept="image/png, image/jpeg, image/gif" name="input-file-preview"/>
                                        <!-- rename it -->
                                    </div>
                                    </span>
                                    </div><!-- /input-group image-preview [TO HERE]-->
                                    <div class="alert alert-info">
                                        <lable style="font-size: 18px;color:white"><i class="fa fa-list"></i> لیست ردیف های تایید شده ی این درخواست : </lable>
                                        <ol>
                                            @foreach($records as $record)
                                                <li style="font-size:16px;">{{$record->title}}</li>
                                            @endforeach
                                        </ol>
                                    </div>
                                </div>
                                {{--<div class="ln_solid"></div>--}}
                                <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-3 col-sm-offset-3 col-xs-offset-1">
                                    {{--<button type="submit" name="edit" id="edit" class="btn btn-primary col-md-3 col-sm-3 col-xs-5">ویرایش</button>--}}
                                    <button type="button" name="add_signature" id="add_signature"
                                            class="btn btn-primary col-md-6 col-sm-3 col-xs-5"> آپلود فاکتور
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $('#signature_priority').change(function() {
                $('#forced').val(0);
            });
        </script>
        <script>
            $(document).on('click','#add_signature',function () {

                var unitId = "";
                $("[name='unit_id']:selected").each(function(){
                    unitId +=$(this).val();
                    $('#unitId').val(unitId);
                });
                //alert(unitId);

                var userId = "";
                $("[name='users']:selected").each(function () {
                    userId += $(this).val();
                    $('#userId').val(userId);
                });
                var file  = $('#file').val();
                var token = $('#token').val();
                if(unitId == '' || unitId == null)
                {
                    swal({
                        title: "",
                        text: 'لطفا واحد مربوطه را انتخاب نمایید',
                        type: "warning",
                        confirmButtonText: "بستن"
                    });
                    return false;
                }
                else if(userId == '' || userId == null)
                {
                    swal({
                        title: "",
                        text: 'لطفا کاربر مربوطه را انتخاب کنید',
                        type: "warning",
                        confirmButtonText: "بستن"
                    });
                    return false;
                }
                else if(file == '' || file == null)
                {
                    swal({
                        title: "",
                        text: 'لطفا فایل مربوط به امضاء را انتخاب کنید',
                        type: "warning",
                        confirmButtonText: "بستن"
                    });
                    return false;
                }else
                {

                    var formData = new FormData($('#dealForm')[0]);
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax
                    ({
                        cache:false,
                        url  : "{{Url('systemManager/addSignature')}}",
                        type : 'POST',
                        processData :false,
                        contentType: false,
                        data : formData,
                        success:function(response)
                        {
                            swal({
                                title: "",
                                text: response,
                                type: "info",
                                confirmButtonText: "بستن"
                            });
                            setTimeout(function () {
                                window.location.reload(true);
                            }, 2000);
                        },error:function(error)
                        {
                            swal({
                                title: "",
                                text: 'خطا در ثبت اطلاعات ، لطفا با بخش پشتیبانی تماس بگیرید',
                                type: "warning",
                                confirmButtonText: "بستن"
                            });
                            console.log(error);
                        }
                    });


                }

            });
        </script>
@endsection