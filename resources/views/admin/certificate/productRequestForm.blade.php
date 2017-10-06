<!DOCTYPE html>
<html>
<head>
    <link href="{{ url('public/dashboard/css/custom-forms.css')}}" rel="stylesheet">
    <script src="{{URL::asset('public/js/jquery_v3.1.1.js')}}"></script>
    <script>
        $(document).on('click','#print',function () {
            window.print();
        })
    </script>
</head>
<body>
<div style="padding:1% 2.5%">
    <h4>نام واحد</h4>
    <h3 style="text-align: right;">فرم شماره 2</h3>
    <h4> « درخواست خرید کالا »</h4>
<table style="direction:rtl;text-align: center" cellpadding="0" cellspacing="0" class="formTable" width="100%">
    <input type="hidden" id="token" value="{{ csrf_token() }}">
    <thead>
    <tr style="border-bottom: 0;">
        <th rowspan="2">ردیف</th>
        <th colspan="3">کالا</th>
        <th rowspan="2">تعداد / مقدار</th>
        <th colspan="2">برآورد قیمت - ریال</th>
    </tr>
    <tr >
        <th style="border-top: 0;">کد کالا</th>
        <th style="border-top: 0;">عنوان کالا</th>
        <th style="border-top: 0;">واحد سنجش</th>
        <th style="border-top: 0;">نرخ</th>
        <th style="border-top: 0;">مبلغ</th>
    </tr>
    </thead>
    <tbody>
    {{-- dynamic tr start--}}
    <?php $i =0; $count = count($productRequestRecords); ?>
    @while($i < $count)
        @foreach($productRequestRecords as $productRequestRecord)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{$productRequestRecord->code}}</td>
                <td>{{$productRequestRecord->title}}</td>
                <td>{{$productRequestRecord->unit_count}}</td>
                <td>{{$productRequestRecord->count}}</td>
                <td>{{number_format($productRequestRecord->rate)}}</td>
                <td>{{number_format($productRequestRecord->rate * $productRequestRecord->count)}}</td>
            </tr>
        @endforeach
    @endwhile
    <tr>
        <td>جمع</td>
        <td colspan="6" style="text-align: left; padding-left: 7.5%;">{{number_format($sum)}}</td>
    </tr>

    <tr>
        <td colspan="2">مسئول انبار</td>
        <td>رئیس امور عمومی</td>
        <td>مدیر / رئیس</td>
        <td colspan="2">مسئول اعتبار / کد ردیابی اعتبار</td>
        <td>مسئول امور مالی</td>
    </tr>
    <tr>
        <td rowspan="2" colspan="2"><img style="width: 100px; height: 100px;" src="{{$storageSupervisorSignature}}"></td>
        <td rowspan="2" style="text-align: right;"><img style="width: 100px; height: 100px;" src="{{$originalJobSupervisorSignature}}"></td>
        <td rowspan="2"><img style="width: 100px; height: 100px;" src="{{$bossSignature}}"></td>
        <td rowspan="2" colspan="2"><img style="width: 100px; height: 100px;" src="{{$creditSupervisorSignature}}"></td>
        <td rowspan="2"><img style="width: 100px; height: 100px;" src="{{$financeSupervisorSignature}}"></td>
    </tr>
    </tbody>
</table>

<h4> « این فرم در سه نسخه تنظیم می گردد : نسخه اول حسابداری، نسخه دوم انبار، نسخه سوم تدارکات »</h4>
</div>
<div align="center">
    <button class="glyphicon glyphicon-print" style="width: 20%; font-size: 150%;" id="print">چاپ</button>
    <i class="fa-print"></i>
</div>
</body>
</html>
