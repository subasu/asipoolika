<!DOCTYPE html>
<html>
<head>
    <link href="{{ url('public/dashboard/css/custom-forms.css')}}" rel="stylesheet">
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
    <tr>
        <td>1</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td>2</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td>3</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    {{-- dynamic tr end--}}
    <tr>
        <td>جمع</td>
        <td colspan="6"></td>
    </tr>
    <tr>
        <td colspan="2">مسئول انبار</td>
        <td>رئیس امور عمومی</td>
        <td>مدیر / رئیس</td>
        <td colspan="3">تأمین اعتبار</td>
    </tr>
    <tr>
        <td rowspan="2" colspan="2"></td>
        <td rowspan="2" style="text-align: right;">
            <input name="t" id="t" type="checkbox" value=""/>
            <label for="t">تحویل مستقیم</label>
            <br>
            <input name="s" id="s" type="checkbox" value=""/>
            <label for="s">سفارش</label>
        </td>
        <td rowspan="2"></td>
        <td colspan="2">مسئول اعتبار / کد ردیابی اعتبار</td>
        <td>مسئول امور مالی</td>
    </tr>
    <tr>
        <td colspan="2">111</td>
        <td></td>
    </tr>
    </tbody>
</table>
<h4> « این فرم در سه نسخه تنظیم می گردد : نسخه اول حسابداری، نسخه دوم انبار، نسخه سوم تدارکات »</h4>
</div>
</body>
</html>