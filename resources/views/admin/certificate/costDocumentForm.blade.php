<!DOCTYPE html>
<html>
<head>
    <title>{{$pageTitle}}</title>
    <link href="{{ url('public/dashboard/css/custom-forms.css')}}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{URL::asset('public/css/sweetalert.css')}}">
    <link href="{{ url('public/dashboard/css/bootstrap.min.css')}}" rel="stylesheet">
    <script src="{{URL::asset('public/js/sweetalert.min.js')}}"></script>
    <script src="{{URL::asset('public/js/jquery_v3.1.1.js')}}"></script>
    <script>
        var recordCount = 0;
        $(document).on('click','#addRow',function () {

            recordCount++;
            $('#recordCount').val(recordCount);
            $('.change tr:first').after
            (
                "<tr>"+
                "<td colspan='1' class='col-md-1'><input type='text' class='form-control required'  name='code[]' placeholder='کد هزینه'></td>"+
                "<td colspan='3' class='col-md-3'><input type='text' class='form-control required'  name='description[]' placeholder='شرح'></td>"+
                "<td colspan='2' class='col-md-2'><input type='text' class='form-control required'  name='moeinOffice[]' placeholder='دفتر معین'></td>"+
                "<td colspan='2' class='col-md-2'><input type='text' class='form-control required'  name='generalPrice[]' placeholder='اصل مبلغ'></td>"+
                "<td colspan='1' class='col-md-1'><input type='text' class='form-control required'  name='deduction[]' placeholder='کسور'></td>"+
                "<td colspan='1' class='col-md-1'><input type='text' class='form-control required'  name='payedPrice[]' placeholder='مبلغ پرداختی'></td>"+
                "<td colspan='1' class='col-md-1'><input type='text' class='form-control required'  name='page[]' placeholder='صفحه'></td>"+
                "<td colspan='1' class='col-md-1'><input type='text' class='form-control required'  name='row[]' placeholder='ردیف'></td>"+
                "<td colspan='1' class='col-md-1'><a class='glyphicon glyphicon-remove-sign' data-toggle='tooltip' title='حذف' style='font-size:18px;'></td>"+
                "</tr>"

            );
        });
    </script>
    <script>

    </script>

    <script>
        $(document).on('click','.glyphicon-remove-sign', function(){
            var recordCount = $('#recordCount').val();
            $(this).closest('tr').remove();
            recordCount--;
            $('#recordCount').val(recordCount);
        });
    </script>
    <script>

        $(document).on('click','#register',function(){
            var formData = $('#myForm').serialize();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax
            ({
               cache : false,
               url: "{{url('admin/saveCostDocument')}}",
               type : "post",
               data : formData,
                beforeSend:function () {
                    var counter = 0;
                    $(".required").each(function() {
                        if ($(this).val() === "") {
                            $(this).css("border" , "red 4px solid");
                            counter++;
                        }
                    });
                    if(counter > 0){
                        alert('تعدادی از فیلدهای فرم خالی است.لطفا فیلدها را پر نمایید سپس ثبت سند را بزنید');
                        return false;
                    }
                },
               success: function(response)
               {
                   alert(response);
                   setInterval(function(){ window.location.reload(); }, 1000);
               },error: function (error) {
                    if (error.status === 422) {
                        var x = error.responseJSON;
                        var errorsHtml = '';
                       // var count = 0;
                        $.each(x, function (key, value) {
                            errorsHtml += value[0] + '\n'; //showing only the first error.
                        });
                        //console.log(count)
                        alert(errorsHtml);
//                        swal({
//                            title: "",
//                            text: errorsHtml,
//                            type: "info",
//                            confirmButtonText: "بستن"
//                        });
                    }
                    if(error.status === 500)
                    {
                        console.log(error);
                        alert('خطایی رخ داده است ، با بخش پشتیبانی تماس بگیرید');
                    }
                }
            });
        })
    </script>

    <script>
        $(document).on('click','#print',function () {

            var token          = $('#token').val();
            var costDocumentId = $('#costDocumentId').val();
            var button         = $(this);
            $.ajax
            ({
                url  : "{{url('admin/formSave')}}/{{5}}",
                type : "post",
                context : button,
                data : {'_token':token , 'costDocumentId' : costDocumentId},
                success : function(response)
                {
                    alert(response);
                    $(button).css('display','none');
                    window.print();
                },
                error : function(error)
                {
                    console.log(error);
                    alert('خطایی رخ داده است ، با بخش پشتیبانی تماس بگیرید')
                }

            });
        })
    </script>
    <style>
        h5, h6 {
            padding-top: 0 !important;
            padding-bottom: 0 !important;
        }
        th{
            font-size: 9px;
            font-wieght:bolder;
            padding: 0;
            line-height: 25px !important;
        }
    </style>
</head>
@if(!empty($id)))

<body  id="body">

<input type="hidden" id="token" value="{{ csrf_token() }}">
<table class="col-md-12" dir="rtl">
    <tr class="col-md-12">
        <td class="" style="width: 31%">
            <h6 style="text-align: center;">بسمه تعالی</h6>
            <h6 style="text-align: right;padding: 0;">بخش هزینه...................................</h6>
            <h6 style="text-align: right;padding: 0;">شماره سند ...................................</h6>
            <h6 style="text-align: right;padding: 0;">تاریخ ..............................................</h6>
            <h6 style="text-align: right;padding: 0;">شماره روزانه .................................</h6>
        </td>
        <td class="" style="width: 36%;">
            <h5 style="text-align: center;padding: 0;">
                <img src="{{url('public/iran-allah.jpg')}}" alt="allah" width="55"/>
            </h5>
            <h5 style="text-align: center;padding: 0;">دانشگاه علوم پزشکی اصفهان</h5>
            <h6 class="text-center">
                شبکه بهداشت و درمان شهرستان خمینی شهر
            </h6>
            <h5 style="padding-bottom: 1%"><b> « سند هزینه »</b></h5>
        </td>
        <td class="" style="width: 33%">
            <h5 style="text-align: center;padding: 0;">
                <img src="{{url('public/isf-Medical.png')}}" style="padding-top:8%;margin-right: 12%;"
                     alt="isf-Medical" width="70"/>
            </h5>
            <p style="font-size: 9px;text-align:justify;margin-right: 12%;">
                از لحاظ پرداخت وجه و بحساب منظور نمودن هزینه انجام شده، فقط نسخه ی سفید معتبر است.
            </p>
        </td>
    </tr>
</table>
<div style="padding:1% 0.5%">

    <form id="myForm">
    <table cellpadding="0" cellspacing="0" class="formTable col-md-12 text-center width100" dir="rtl">
        <thead >
        <tr style="border-bottom: 0 solid #000;padding: 0px;margin:0px;">
            <th colspan="6" class="col-md-6 text-right" >اسم گیرنده وجه :</th>
            <th colspan="7" class="col-md-6 text-right">نشانی کامل :</th>
        </tr>
        <tr>
            <th colspan="1" rowspan="2" class="col-md-1"  style="padding: 0px !important;" >کد هزینه</th>
            <th colspan="3" rowspan="2" class="col-md-3"  style="padding: 0px !important;">شرح</th>
            <th colspan="2" rowspan="2" class="col-md-2"  style="padding: 0px !important;">دفتر معین</th>
            <th colspan="2" rowspan="2" class="col-md-2"  style="padding: 0px !important;">اصل مبلغ</th>
            <th colspan="1" rowspan="2" class="col-md-1"  style="padding: 0px !important;">کسور</th>
            <th colspan="1" rowspan="2" class="col-md-1"  style="padding: 0px !important;" >مبلغ پرداختی</th>
            <th colspan="2" rowspan="1" class="col-md-2"  style="padding: 0px !important;">دفتر اعتبارات</th>
            <th colspan="2" rowspan="2" class="col-md-2"  >حذف</th>
        </tr>

        <tr style="border-bottom: 0;">
            <th colspan="1">صفحه</th>
            <th colspan="1" class="col-md-4">ردیف</th>
        </tr>
        </thead>

        <tbody class="change">
        {{--dynamic tr start--}}

            {!! csrf_field() !!}
        <tr>
            {{--<td colspan="1" class="col-md-1"><input type="text" id="code" name="code[]" class="form-control required" placeholder="کد هزینه"></td>--}}
            {{--<td colspan="3" class="col-md-3"><input type="text" id="description" name="description[]"  class="form-control required" placeholder="شرح"></td>--}}
            {{--<td colspan="2" class="col-md-2"><input type="text" id="moeinOffice" name="moeinOffice[]" class="form-control required" placeholder="دفتر معین"></td>--}}
            {{--<td colspan="2" class="col-md-2"><input type="text" id="generalPrice" name="generalPrice[]" class="form-control required" placeholder="اصل مبلغ"></td>--}}
            {{--<td colspan="1" class="col-md-1"><input type="text" id="deduction" name="deduction[]" class="form-control required" placeholder="کسور"></td>--}}
            {{--<td colspan="1" class="col-md-1"><input type="text" id="payedPrice" name="payedPrice[]" class="form-control required" placeholder="مبلغ پرداختی"></td>--}}
            {{--<td colspan="1" class="col-md-1"><input type="text" id="page" name="page[]" class="form-control required" placeholder="صفحه"></td>--}}
            {{--<td colspan="1" class="col-md-1"><input type="text" id="row" name="row[]" class="form-control required" placeholder="ردیف"></td>--}}
            <input type="hidden" id="requestId" name="requestId" value="{{$id}}">
            <input type="hidden" id="recordCount" name="recordCount" value="">
            <input type="hidden" id="bodyContent" name="bodyContent" value="">
        </tr>

        {{--dynamic tr end--}}
        <tr >
            <td colspan="2" class="col-md-2">مدارک پیوست</td>
            <td colspan="2" class="col-md-2"> ...</td>
            <td colspan="2" class="col-md-2">جمع</td>
            <td colspan="2" class="col-md-2">1</td>
            <td colspan="1" class="col-md-1">2</td>
            <td colspan="1" class="col-md-1">3</td>
            <td colspan="1" class="col-md-1">4</td>
            <td colspan="1" class="col-md-1">5</td>
            <td colspan="1" class="col-md-1"></td>
        </tr>
        <tr>
            <td colspan="6" class="col-md-6 text-justify">
                <h6 class="text-justify"><b>تأمین اعتبار:
                    </b>
                    در تاریخ .... مبلغ ... ریال از محل کد شماره .... برابر مقررات مالی و تأمین اعتبار شده است.
                </h6>
                <h6><b>
                    اعتبارات
                    </b></h6>
            </td>
            <td colspan="7" class="col-md-6">
                <h6 class="text-justify"><b>رسیدگی و نظارت مالی:
                    </b>
                    این سند هزینه یا مدارک پیوست و ارقام مندرج در آن برابر مقررات مالی سازمان رسیدگی گردید، پرداخت ان
                    بلامانع است. </h6>
                <h6><b>
                    رسیدگی
                    </b></h6>
            </td>
        </tr>
        <tr>
            <td colspan="13" class="col-md-12 text-justify">
                <h6 class="text-justify">
                    اینجانب ... تنظیم کننده سند گواهی می نمایم که سند هزینه بموجب اناد و مدارک پیوست برابر مقررات مالی
                    سازمان تنظیم گردیده و قبلا وجهی بابت این هزینه پرداخت نشده است. </h6>
                <b class="col-md-4 text-rigth pull-right ">
                    تنظیم اسناد
                </b>
                <b class="col-md-4 text-center">
                    امور مالی
                </b>
                <b class="col-md-4 text-right pull-left">
                    رئیس واحد
                </b>
                <br>
            </td>
        </tr>
        <tr style="border-top: 1px solid #000 !important;">
            <td colspan="13" class="col-md-12 text-justify">
                <h6 class="text-justify"><b>گواهی و رسید گیرنده پول :
                    </b>
                    مبلغ ( بحروف ) ............................ ریال طی چک / نقداََ ...... دریافت گردیده است.
                </h6>
                <p class="text-left" style="font-weight:bolder;">
                    امضاء
                </p></td>
        </tr>
        </tbody>

    </table>
    </form>
</div>
    <div align="center">
        <input type="button" style="margin-top: 2%; font-size: 120%;" value="اضافه کردن سطر به جدول"  class="btn btn-info" id="addRow"></input>
        <input type="button" style="margin-top: 2%; font-size: 120%;" value="ثبت سند هزینه"  class="btn btn-info" id="register"></input>

    </div>
</body>

    @endif

@if(!empty($costDocumentsRecords))
<body>
<table class="col-md-12" dir="rtl">
    <tr class="col-md-12">
        <td class="" style="width: 31%">
            <h6 style="text-align: center;">بسمه تعالی</h6>
            <h6 style="text-align: right;padding: 0;">بخش هزینه...................................</h6>
            <h6 style="text-align: right;padding: 0;">شماره سند ...................................</h6>
            <h6 style="text-align: right;padding: 0;">تاریخ ..............................................</h6>
            <h6 style="text-align: right;padding: 0;">شماره روزانه .................................</h6>
        </td>
        <td class="" style="width: 36%;">
            <h5 style="text-align: center;padding: 0;">
                <img src="{{url('public/iran-allah.jpg')}}" alt="allah" width="55"/>
            </h5>
            <h5 style="text-align: center;padding: 0;">دانشگاه علوم پزشکی اصفهان</h5>
            <h6 class="text-center">
                شبکه بهداشت و درمان شهرستان خمینی شهر
            </h6>
            <h5 style="padding-bottom: 1%"><b> « سند هزینه »</b></h5>
        </td>
        <td class="" style="width: 33%">
            <h5 style="text-align: center;padding: 0;">
                <img src="{{url('public/isf-Medical.png')}}" style="padding-top:8%;margin-right: 12%;"
                     alt="isf-Medical" width="70"/>
            </h5>
            <p style="font-size: 9px;text-align:justify;margin-right: 12%;">
                از لحاظ پرداخت وجه و بحساب منظور نمودن هزینه انجام شده، فقط نسخه ی سفید معتبر است.
            </p>
        </td>
    </tr>
</table>
<div style="padding:1% 0.5%">

    <form id="myForm">
        <table cellpadding="0" cellspacing="0" class="formTable col-md-12 text-center width100" dir="rtl">
            <thead >
            <tr style="border-bottom: 0 solid #000;padding: 0px;margin:0px;">
                <th colspan="6" class="col-md-6 text-right" >اسم گیرنده وجه :</th>
                <th colspan="7" class="col-md-6 text-right">نشانی کامل :</th>
            </tr>
            <tr>
                <th colspan="1" rowspan="2" class="col-md-1"  style="padding: 0px !important;" >کد هزینه</th>
                <th colspan="3" rowspan="2" class="col-md-3"  style="padding: 0px !important;">شرح</th>
                <th colspan="2" rowspan="2" class="col-md-2"  style="padding: 0px !important;">دفتر معین</th>
                <th colspan="2" rowspan="2" class="col-md-2"  style="padding: 0px !important;">اصل مبلغ</th>
                <th colspan="1" rowspan="2" class="col-md-1"  style="padding: 0px !important;">کسور</th>
                <th colspan="1" rowspan="2" class="col-md-1"  style="padding: 0px !important;" >مبلغ پرداختی</th>
                <th colspan="2" rowspan="1" class="col-md-2"  style="padding: 0px !important;">دفتر اعتبارات</th>
            </tr>

            <tr style="border-bottom: 0;">
                <th colspan="1">صفحه</th>
                <th colspan="1" class="col-md-4">ردیف</th>
            </tr>
            </thead>

            <tbody class="change">
        @foreach($costDocumentsRecords as $costDocumentsRecord)

            <tr>
                <td colspan="1" class="col-md-1">{{$costDocumentsRecord->code}}</td>
                <td colspan="3" class="col-md-3">{{$costDocumentsRecord->description}}</td>
                <td colspan="2" class="col-md-2">{{$costDocumentsRecord->moein_office}}</td>
                <td colspan="2" class="col-md-2">{{number_format($costDocumentsRecord->general_price)}}</td>
                <td colspan="1" class="col-md-1">{{number_format($costDocumentsRecord->deduction)}}</td>
                <td colspan="1" class="col-md-1">{{number_format($costDocumentsRecord->payed_price)}}</td>
                <td colspan="1" class="col-md-1">{{$costDocumentsRecord->page}}</td>
                <td colspan="1" class="col-md-1">{{$costDocumentsRecord->row}}</td>
                <input type="hidden" id="costDocumentId" value="{{$costDocumentsRecord->cost_document_id}}">
            </tr>
        @endforeach
        <tr >
            <td colspan="2" class="col-md-2">مدارک پیوست</td>
            <td colspan="2" class="col-md-2"> ...</td>
            <td colspan="2" class="col-md-2">جمع</td>
            <td colspan="2" class="col-md-2">1</td>
            <td colspan="1" class="col-md-1">2</td>
            <td colspan="1" class="col-md-1">3</td>
            <td colspan="1" class="col-md-1">4</td>
            <td colspan="1" class="col-md-1">5</td>

        </tr>
        <tr>
            <td colspan="6" class="col-md-6 text-justify">
                <h6 class="text-justify"><b>تأمین اعتبار:
                    </b>
                    در تاریخ .... مبلغ ... ریال از محل کد شماره .... برابر مقررات مالی و تأمین اعتبار شده است.
                </h6>
                <h6><b>
                        اعتبارات
                    </b></h6>
            </td>
            <td colspan="7" class="col-md-6">
                <h6 class="text-justify"><b>رسیدگی و نظارت مالی:
                    </b>
                    این سند هزینه یا مدارک پیوست و ارقام مندرج در آن برابر مقررات مالی سازمان رسیدگی گردید، پرداخت ان
                    بلامانع است. </h6>
                <h6><b>
                        رسیدگی
                    </b></h6>
            </td>
        </tr>
        <tr>
            <td colspan="13" class="col-md-12 text-justify">
                <h6 class="text-justify">
                    اینجانب ... تنظیم کننده سند گواهی می نمایم که سند هزینه بموجب اناد و مدارک پیوست برابر مقررات مالی
                    سازمان تنظیم گردیده و قبلا وجهی بابت این هزینه پرداخت نشده است. </h6>
                <b class="col-md-4 text-rigth pull-right ">
                    تنظیم اسناد
                </b>
                <b class="col-md-4 text-center">
                    امور مالی
                </b>
                <b class="col-md-4 text-right pull-left">
                    رئیس واحد
                </b>
                <br>
            </td>
        </tr>
        <tr style="border-top: 1px solid #000 !important;">
            <td colspan="13" class="col-md-12 text-justify">
                <h6 class="text-justify"><b>گواهی و رسید گیرنده پول :
                    </b>
                    مبلغ ( بحروف ) ............................ ریال طی چک / نقداََ ...... دریافت گردیده است.
                </h6>
                <p class="text-left" style="font-weight:bolder;">
                    امضاء
                </p></td>
        </tr>
            </tbody>

        </table>
    </form>
</div>
<div align="center">
    <input type="hidden" id="token" value="{{ csrf_token() }}">
    <input type="button" value="چاپ سند هزینه"  style="font-size: 120%; margin-top: 2%; !important;" class="btn btn-info" id="print">
</div>
</body>
@endif

</html>