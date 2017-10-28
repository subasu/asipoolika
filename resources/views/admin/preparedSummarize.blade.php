@extends('layouts.adminLayout')
@section('content')
    <style>
        .margin-2percent{
            margin-top: 2%;
        }
    </style>

    <div id="modal" class="modal fade" role="dialog" style="direction: rtl;text-align: right">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">راهنمای ثبت خلاصه تنظیمی</h4>
                </div>
                <div class="modal-body">
                    <p> در صورتی که نیاز به اضافه کردن ردیف های جدید دارید، میتوانید روی دکمه اضافه کردن ردیف کلیک نمایید و سپس پس از ثبت ردیف های جدید باید منتظر تایید کارپرداز مربوطه بمانید تا نسخه چاپی آماده شود</p>
                    <p>و در صورتی که ردیف های نمایش داده شده را به عنوان خلاصه تنظیمی قبول دارید کافی است روی دکمه ثبت خلاصه تنظیمی کلیک نمایید و سپس منتظر تایید کارپرداز بمانید تا نسخه چاپی نیز آماده گردد.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info col-md-12" data-dismiss="modal">متوجه شدم</button>
                </div>
            </div>

        </div>
    </div>



    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>فاکتورهای مربوط به درخواست شماره {{$factors[0]->request_id}}</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link" data-toggle="tooltip" title="جمع کردن"><i
                                        class="fa fa-chevron-up"></i></a>
                        </li>
                        <li><a class="close-link" data-toggle="tooltip" title="بستن"><i class="fa fa-close"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="col-md-12">



                </div>
                <div class="x_content">
                    <table style="direction:rtl;text-align: center" id="example"
                           class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                        <input type="hidden" id="token" value="{{ csrf_token() }}">
                        <thead>
                        <tr>
                            <th style="text-align: center" class="col-md-1">شناسه</th>
                            <th style="text-align: center" class="">شرح</th>
                            <th style="text-align: center;border-right: 1px solid #e0e0d1">مبلغ</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($factors as $factor)
                            <tr class="unit">
                                <td>
                                    {{$factor->id}}
                                </td>
                                <td>
                                     فاکتور شماره : {{$factor->factor_number}}
                                </td>
                                <td style="border-right: 1px solid #e0e0d1">
                                    {{number_format($factor->final_price)}}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>


                    <form id="dealForm">
                        <table style="direction:rtl;text-align: center" id="example"
                               class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <input type="hidden" id="token" value="{{ csrf_token() }}">
                            <thead>
                            <tr>
                                <th style="text-align: center" class="">شرح</th>
                                <th style="text-align: center;" >مبلغ</th>
                                <th style="text-align: center;border-right: 1px solid #e0e0d1" >حذف</th>
                            </tr>
                            </thead>

                            {!! csrf_field() !!}
                            <tbody id="change">

                            </tbody>
                            <input type="hidden" id="requestId" name="requestId" value="{{$factors[0]->request_id}}">
                            <input type="hidden" id="recordCount" name="recordCount" value="0">
                        </table>
                        <input type="button" id="reg" value="ثبت ردیف های جدید" class="btn btn-success" style="margin-left:43% ; display: none; width: 16%;">
                    </form>
                    <input type="hidden" id="token" value="{{ csrf_token() }}">
                    <input type="button" id="finish" value="ثبت خلاصه تنظیمی" class="btn btn-success col-md-2 margin-2percent  col-md-offset-3">
                    <input type="button" id="guide" value="راهنمای استفاده از صفحه" class="btn btn-info col-md-2 margin-2percent">
                    <input type="button" id="addRow" value="اضافه کردن ردیف" class="btn btn-primary col-md-2 margin-2percent">
                </div>
            </div>
        </div>
<<<<<<< HEAD


    <script>
=======
    {{--<script>--}}
>>>>>>> 61187356bb85c3e4448bc8334b789e3be84f49b8

            {{--function formatNumber (num) {--}}
                {{--return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")--}}
            {{--}--}}

<<<<<<< HEAD
                //var totalPrice = $("#finalPrice");
</script>
    <script>
            $(document).on('keyup','#totalPrice',function(){
                var v0 = $(this).val();
                var v1 = v0.split(',').join('');
                var v2 = formatNumber(v1);
                $(this) . val(v2);
            })
    </script>
=======
                {{--//var totalPrice = $("#finalPrice");--}}
{{--</script>--}}
    {{--<script>--}}
                {{--$(document).on('keyup','#totalPrice',function(){--}}
                    {{--var v0 = $(this).val();--}}
                    {{--var v1 = v0.split(',').join('');--}}
                    {{--var v2 = formatNumber(v1);--}}
                    {{--$(this) . val(v2);--}}
                {{--})--}}

    {{--</script>--}}
>>>>>>> 61187356bb85c3e4448bc8334b789e3be84f49b8
        <script>
            $(document).on('click','#addRow',function(){
                var recordCount = $('#recordCount').val();
                var newRecordCount = ++recordCount;
                $('#recordCount').val(newRecordCount);
                $('#reg').css('display','block');
                $('#change').append
<<<<<<< HEAD
                    (
                        "<tr>"+
                            "<td><input type='number' class='form-control required' name='description[]' id='description'></td>"+
                            "<td><input type='text' class='form-control required' name='totalPrice[]'  id='totalPrice'></td>"+
                            "<td style='border-right: 1px solid #e0e0d1;'><a  class='btn btn-danger remove_row' data-toggle='tooltip' title='حذف' style='font-size:18px;'><span class='fa fa-trash'></span></a></td>"+
                        "</tr>"
=======
                            (
                                "<tr>"+
                                    "<td><input type='number' class='form-control required' name='description[]' id='description'></td>"+
                                    "<td><input type='number' class='form-control required' name='totalPrice[]'  id='totalPrice'></td>"+
                                    "<td><a  class='btn btn-danger remove_row' data-toggle='tooltip' title='حذف' style='font-size:18px;'><span class='fa fa-trash'></span></a></td>"+
                                "</tr>"
>>>>>>> 61187356bb85c3e4448bc8334b789e3be84f49b8

                    );
            });
        </script>

        <script>
            $(document).on('click','#reg',function(){
               var requestId = $('#requestId').val();
               var formData = $('#dealForm').serialize();
               var counter = 0;
               $('.required').each(function () {
                   if($(this).val() == '')
                   {
                       $(this).css('border-color','red');
                       counter++;
                   }
               });
                  if(counter > 0)
                  {
                      swal
                      ({
                          title: '',
                          text: 'تعدادی از فیلدهای فرم خالی است.لطفا فیلدها را پر نمایید سپس ثبت نهایی را بزنید',
                          type:'warning',
                          confirmButtonText: "بستن"
                      });
                      return false;
                  }else {
                      swal({
                              title: "کاربر گرامی در نظر داشته باشید در صورتی که خلاصه تنظیمی برای این درخواست ثبت گردد دیگر امکان آپلود فاکتور و اضافه کردن ردیف های جدید وجود ندارد ، آیا درخواست خود را ثبت می نمایید؟",
                              text: "",
                              type: "warning",
                              showCancelButton: true,
                              confirmButtonColor: "	#5cb85c",
                              cancelButtonText: "خیر ، منصرف شدم",
                              confirmButtonText: "بله ثبت شود",
                              closeOnConfirm: true,
                              closeOnCancel: true
                          },
                          function (isConfirm) {
                              if (isConfirm) {
                                  $.ajaxSetup({
                                      headers: {
                                          'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                                      }
                                  });
                                  $.ajax
                                  ({
                                      url: "{{url('admin/savePreparedSummarize')}}",
                                      type: 'post',
                                      data: formData,
                                      context : requestId,
                                      success: function (response) {
                                          swal
                                          ({
                                              title: '',
                                              text: response,
                                              type: 'info',
                                              confirmButtonText: "بستن"
                                          });
                                          setTimeout(function () {
                                              window.location.href='../confirmedRequestDetails/'+requestId;
                                          },2000);
                                      }, error: function (error) {
                                          swal
                                          ({
                                              title: '',
                                              text: 'خطایی رخ داده است ، لطفا با بخش پشتیبانی تماس بگیرید',
                                              type: 'info',
                                              confirmButtonText: "بستن"
                                          });
                                          console.log(error);
                                      }
                                  })

                              }

                          });
                  }
            });
        </script>
        <script>
            $(document).on('click','.remove_row',function () {
                $(this).closest('tr').remove();
                var recordCount = $('#recordCount').val();
                var newRecordCount = --recordCount;
                $('#recordCount').val(newRecordCount);
            })
        </script>
        <script>
            $(document).on('click','#guide',function(){
               $('#modal').modal('show');
            });
        </script>
        <script>
            $(document).on('click','#finish',function () {
                var requestId = $('#requestId').val();
                var token     = $('#token').val();
                swal({
                        title: "کاربر گرامی در نظر داشته باشید در صورتی که خلاصه تنظیمی برای این درخواست ثبت گردد دیگر امکان آپلود فاکتور و اضافه کردن ردیف های جدید وجود ندارد ، آیا درخواست خود را ثبت می نمایید؟",
                        text: "",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "	#5cb85c",
                        cancelButtonText: "خیر ، منصرف شدم",
                        confirmButtonText: "بله ثبت شود",
                        closeOnConfirm: true,
                        closeOnCancel: true
                    },
                    function (isConfirm) {
                        if (isConfirm) {
                            $.ajax
                            ({
                                url: "{{url('admin/updatePreparedSummarize')}}",
                                type: "post",
                                context : requestId,
                                data: {'requestId': requestId, '_token': token},
                                success: function (response) {
                                    swal
                                    ({
                                        title: '',
                                        text: response,
                                        type: 'info',
                                        confirmButtonText: "بستن"
                                    });
                                    setTimeout(function () {
                                        window.location.href = '../confirmedRequestDetails/'+requestId;
                                    }, 2000);
                                }, error: function (error) {
                                    swal
                                    ({
                                        title: '',
                                        text: 'خطایی رخ داده است ، لطفا با بخش پشتیبانی تماس بگیرید',
                                        type: 'info',
                                        confirmButtonText: "بستن"
                                    });
                                    console.log(error);
                                }
                            })
                        }
                    });
            });
        </script>
@endsection