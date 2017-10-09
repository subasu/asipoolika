<!DOCTYPE html>
<html>
<head>
    <link href="{{ url('public/dashboard/css/custom-forms.css')}}" rel="stylesheet">
    <link href="{{ url('public/dashboard/css/bootstrap.min.css')}}" rel="stylesheet">
    <script src="{{URL::asset('public/js/jquery_v3.1.1.js')}}"></script>
    <script>
        $(document).on('click','#addRow',function () {
            $('#change tr:first').after
            (
                "<tr>"+
                "<td colspan='1' class='col-md-1'>1</td>"+
                "<td colspan='3' class='col-md-3'>vvv</td>"+
                "<td colspan='2' class='col-md-2'>vv</td>"+
                "<td colspan='2' class='col-md-2'>vvv</td>"+
                "<td colspan='1' class='col-md-1'>vvv</td>"+
                "<td colspan='1' class='col-md-1'>vvv</td>"+
                "<td colspan='1' class='col-md-1'>vvv</td>"+
                "<td colspan='1' class='col-md-1'>vvv</td>"+
                "</tr>"
            );

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


    <table cellpadding="0" cellspacing="0" class="formTable col-md-12 text-center width100" dir="rtl">
        <thead>
        <tr style="border-bottom: 0 solid #000;padding: 0px;margin:0px;">
            <th colspan="6" class="col-md-6 text-right" >اسم گیرنده وجه :</th>
            <th colspan="7" class="col-md-6 text-right">نشانی کامل :</th>
        </tr>
        <tr>
            <th colspan="1" rowspan="2" class="col-md-1" style="padding: 0px !important;" >کد هزینه</th>
            <th colspan="3" rowspan="2" class="col-md-3" style="padding: 0px !important;">شرح</th>
            <th colspan="2" rowspan="2" class="col-md-2" style="padding: 0px !important;">دفتر معین</th>
            <th colspan="2" rowspan="2" class="col-md-2" style="padding: 0px !important;">اصل مبلغ</th>
            <th colspan="1" rowspan="2" class="col-md-1" style="padding: 0px !important;">کسور</th>
            <th colspan="1" rowspan="2" class="col-md-1" style="padding: 0px !important;" >مبلغ پرداختی</th>
            <th colspan="2" rowspan="1" class="col-md-2" style="padding: 0px !important;">دفتر اعتبارات</th>
        </tr>

        <tr style="border-bottom: 0;">
            <th colspan="1">صفحه</th>
            <th colspan="1" class="col-md-4">ردیف</th>
        </tr>
        </thead>
        <tbody id="change">
        {{--dynamic tr start--}}

        <tr>
            <td colspan="1" class="col-md-1">1</td>
            <td colspan="3" class="col-md-3">vvv</td>
            <td colspan="2" class="col-md-2">vv</td>
            <td colspan="2" class="col-md-2">vvv</td>
            <td colspan="1" class="col-md-1">vvv</td>
            <td colspan="1" class="col-md-1">vvv</td>
            <td colspan="1" class="col-md-1">vvv</td>
            <td colspan="1" class="col-md-1">vvv</td>
        </tr>
        {{--dynamic tr end--}}
        <tr>
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
</div>
    <div align="center">
        <button style="font-family: Tahoma; margin-top: 2%;" class="btn btn-info" id="addRow">اضافه کردن سطر به جدول</button>
    </div>
</body>
</html>