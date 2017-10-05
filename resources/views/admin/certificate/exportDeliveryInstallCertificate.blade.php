<!DOCTYPE html>
<html>
<head>
    <link href="{{ URL::asset('public/dashboard/css/custom-forms.css')}}" rel="stylesheet">
    <script src="{{URL::asset('public/js/jquery_v3.1.1.js')}}"></script>
    <script>
        $(document).on('click','#print',function () {
            window.print();
        })
    </script>
</head>
<body>
<div style="padding:1% 2.5%">
    <h3 class="text-center">
        دانشگاه علوم پزشکی و خدمات بهداشتی و درمانی استان اصفهان
    </h3><br>
    <div class="row">
        <div class="container">
            <table class="col-md-12 " dir="rtl">
                <tr class="col-md-12">
                    <th class="remove-border col-md-6 ">تاریخ :</th>
                    <th class="remove-border col-md-6 ">......</th>
                </tr>
                <tr>
                    <th class="remove-border col-md-6 ">شماره :</th>
                    <th class="remove-border col-md-6 ">......</th>
                </tr>
            </table>
        </div>
    </div>
    <br>
    <h3 class="text-center">« صورت جلسه تحویل کالا و نصب »</h3><br>
    <h4 dir="rtl" style="text-align: justify;">بدینوسیله گواهی می شود خدمات انجام شده به شرح زیر توسط
        شرکت/
        فروشگاه
        .........................
        جهت واحد
        .........................
        به آقای/خانم
        .........................
        تحویل گردید و پرداخت شده است.</h4>
    <br>
    <table class="formTable col-md-12 width100 border-right" dir="rtl">
        <thead>
        <tr class=" padding-formTable">
            <th class="col-md-4" colspan="2">شرح</th>
            <th class="col-md-2">تعداد</th>
            <th class="col-md-3"> مبلغ کل (ریال)</th>
        </tr>
        </thead>
        <tbody>
        @foreach($certificateRecords as $certificateRecord)
        <tr>
            <td class="col-md-4" colspan="2">{{$certificateRecord->description}}</td>
            <td class="col-md-2">{{$certificateRecord->count}}</td>
            <td class="col-md-3">{{number_format($certificateRecord->price)}}</td>
        </tr>
        @endforeach
        <tr>
            <td class="col-md-4" colspan="2">جمع کل به حروف</td>
            <td class="col-md-2">جمع کل به عدد</td>
            <td class="col-md-3">{{number_format($sum)}}</td>
        </tr>
        <tr>
            <th class="col-md-3">تحویل گیرنده</th>
            <th class="col-md-3">مسئول واحد</th>
            <th class="col-md-3">کارپرداز</th>
            <th class="col-md-3">رئیس واحد</th>
        </tr>
        @foreach($certificateRecords as $certificateRecord)
            <tr>
                <td class="col-md-3">{{$certificateRecord->user->name .chr(10). $certificateRecord->user->family}}</td>
                <td class="col-md-3">{{$unitSupervisorName .chr(10).$unitSupervisorFamily}}</td>
                <td class="col-md-3">{{$certificateRecord->requestRecord->request->supplier->name .chr(10).$certificateRecord->requestRecord->request->supplier->family}}</td>
                <td class="col-md-3">رئیس سازمان</td>
            </tr>
        @endforeach
        </tbody>

    </table>
    <br><br><br>
    <div align="center">
        <button  class="glyphicon glyphicon-print" style="width: 20%; font-size: 150%;" id="print">چاپ</button>
        <i class="fa-print"></i>
    </div>
</div>
</body>
</html>