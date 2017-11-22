@extends('layouts.adminLayout')
@section('content')
    <style>
        .padding-right-1px{padding-right: 1px !important;}
    </style>
    <!-- page content -->
    <div id="modal" class="modal fade" role="dialog" style="direction: rtl;text-align: right">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">اطلاع رسانی</h4>
                </div>
                <div class="modal-body">
                    <p>در نظر داشته باشید دسترسی به این صفحه جهت آپلود فاکتور فقط و فقط یکبار امکان پذیر است ، لذا در آپلود فاکتورهای مورد نظر خود دقت فرمائید.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info col-md-12" data-dismiss="modal">متوجه شدم</button>
                </div>
            </div>

        </div>
    </div>


    <div class="" role="main">
        <div class="">
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-8 col-sm-12 col-xs-12 col-md-offset-2">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>آپلود قبض انبار درخواست شماره : {{$id}}</h2>
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
                            <form  enctype="multipart/form-data" id="dealForm" style="direction: rtl;">
                                {{ csrf_field() }}
                                <input type="hidden" name="dealTypeName" id="dealTypeName" value="">
                                <label style="font-size: 20px;margin-bottom: 10px;"
                                       class="control-label pull-right col-md-12 col-sm-12 col-xs-12 form-group">
                                </label>
                                <div class="col-md-12">
                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group pull-right">
                                        <label class="control-label col-md-5 col-sm-4 col-xs-12 padding-right-1px pull-right" for=""> شماره فاکتور :
                                        </label>
                                        <input type="number" min="0" class="form-control" name="factorNumber" id="factorNumber">
                                    </div>

                                    <lable style="font-size: 16px;">توضیحات : حجم فایل قبض انبار نباید بیش از 150 کیلو بایت باشد ، پسوند فایل قبض انبار باید از نوع  png یا jpg باشد.</lable>
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
                                        <input type="file" id="image" name="image"  />
                                        <!-- rename it -->
                                    </div>
                                    </span>
                                    </div><!-- /input-group image-preview [TO HERE]-->
                                    <div class="alert alert-info">
                                        <lable style="font-size: 18px;color:white"><i class="fa fa-list"></i> لیست ردیف های تایید شده ی این درخواست : </lable>
                                        <ol>
                                            @foreach($records as $record)
                                                <li style="font-size:16px;">{{decrypt($record->title)}}</li>
                                            @endforeach
                                        </ol>
                                    </div>
                                </div>
                                {{--<div class="ln_solid"></div>--}}
                                <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-3 col-sm-offset-3 col-xs-offset-1">
                                    {{--<button type="submit" name="edit" id="edit" class="btn btn-primary col-md-3 col-sm-3 col-xs-5">ویرایش</button>--}}

                                    <button type="button" name="addWarehouseBill" id="addWarehouseBill"
                                            class="btn btn-success col-md-5 col-sm-3 col-xs-5">  آپلود قبض انبار
                                    </button>

                                    <input type="hidden" id="requestId" name="requestId" value="{{$id}}">
                                    <input type="hidden" id="token" value="{{ csrf_token() }}">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>





        <script>
            $(document).on('click','#addWarehouseBill',function(){




                var requestId = $('#requestId').val();
                var formData      = new FormData($('#dealForm')[0]);


                $.ajax
                ({
                    cache:false,
                    url  : "{{Url('admin/checkFile')}}/{{'warehouse'}}",
                    type : 'POST',
                    processData :false,
                    contentType: false,
                    context : requestId,
                    data : formData,

                    success:function(response)
                    {
                        if(response == 'قبض انبار  مورد نظر شما آپلود گردید')
                        {
                            swal({
                                title: "",
                                text: response,
                                type: "info",
                                confirmButtonText: "بستن"
                            });
                            setTimeout( function(){window.location.href = '../confirmedRequestDetails/'+requestId},3000);
                        }else
                        {
                            swal({
                                title: "",
                                text: response,
                                type: "info",
                                confirmButtonText: "بستن"
                            });
                        }

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
        <script>
            $(document).on('click','#check',function(){
                var requestId = $(this).attr('content');
                var token     = $('#token').val();

                $.ajax
                ({
                    url  : "{{url('admin/checkPreparedSummarize')}}",
                    type : 'post',
                    context : requestId,
                    data : {'requestId' : requestId , '_token' : token},
                    success : function (resposne) {
                        if(resposne >= 2)
                        {
                            window.location.href = '../preparedSummarize/'+requestId ;
                        }else
                        {
                            swal({
                                title: "",
                                text: 'تعداد فاکتورهای آپلود شده به حدی نرسیده است که امکان ثبت خلاصه تنظیمی وجود داشته باشد',
                                type: "info",
                                confirmButtonText: "بستن"
                            });
                            return false;
                        }

                    },error : function(error)
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