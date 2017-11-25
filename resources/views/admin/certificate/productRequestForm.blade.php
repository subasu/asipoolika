@extends('layouts.formLayout')

    <title>{{$pageTitle}}</title>
    <link href="{{ url('public/dashboard/css/custom-forms.css')}}" rel="stylesheet">
    <script src="{{URL::asset('public/js/jquery_v3.1.1.js')}}"></script>
    <script>
        $(document).ready(function(){
            var number=$('#number1').val();
            $('#sumToPersian').text(number.toPersian()+' ریال');
        });
        $(document).on('click','#print',function () {

            //var body = $('#body')[0].innerHTML;
            //var token = $('#token').val();
            var requestId = $('#requestId').val();
            var button    = $(this);
            var formId = $('#formId').val();
            jQuery.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax
            ({
                cache : false,
                url  : "{{url('admin/formSave')}}/{{2}}",
                type : "post",
                context : button,
                data : {'requestId' : requestId},
                success : function(response)
                {
                    alert(response);
                    $(button).css('display','none');
                    window.print();
                },
                error : function(error)
                {
                    console.log(error);
                    alert('خطایی رخ داده است ، با بخش پشتیبانی تماس بگیرید');
                }

            });
        })
    </script>
<script src="{{URL::asset('public/dashboard/numberToLetter/num2persian.js')}}"></script>
<script src="{{URL::asset('public/dashboard/numberToLetter/num2persian.min.js')}}"></script>
@if(!empty($productRequestRecords))
<body id="body">

<div style="padding:1% 2.5%">
    <h4>نام واحد</h4>
    <h3 style="text-align: right;">« 2 » فرم شماره </h3>
    <h3 style="margin-bottom: 10px;"> « درخواست خرید کالا »</h3>
<table style="direction:rtl;text-align: center;font-size: 15px;" cellpadding="0" cellspacing="0" class="formTable" width="100%">

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
        <?php  $i=0; ?>
        @foreach($productRequestRecords as $productRequestRecord)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{$productRequestRecord->code}}</td>
                <td>{{$productRequestRecord->title}}</td>
                <td>{{$productRequestRecord->unit_count}}</td>
                <td>{{$productRequestRecord->count}}</td>
                <td>{{number_format($productRequestRecord->rate)}}</td>
                <td>{{number_format($productRequestRecord->rate) * $productRequestRecord->count}}</td>
                <input type="hidden" id="requestId" value="{{$productRequestRecord->request_id}}">
            </tr>
        @endforeach

    <tr>
        <td colspan="6" style="text-align: left;"> جمع
            <input type="hidden" value="{{$sum}}" id="number1">
            <br><p id="sumToPersian" style="font-size:14px;color:black;"></p></td>
        <td colspan="6" style="padding-right: 10px;padding-left: 20px;">{{number_format($sum)}}

        </td>
    </tr>

    <tr>
        <td colspan="1" class="col-md-2"><span>مسئول انبار :</span><span>{{ chr(10) }}</span><span>{{$storageSupervisorFullName}}</span></td>
        <td colspan="1" class="col-md-2"><span>رئیس امور عمومی :</span><span>{{ chr(10) }}</span><span>{{$originalJobSupervisorFullName}}</span></td>
        <td colspan="1" class="col-md-2"><span>مدیر / رئیس :</span><span>{{ chr(10) }}</span><span>{{$bossFullName}}</span></td>
        <td colspan="2" class="col-md-2"><span>مسئول اعتبار :</span><span>{{ chr(10) }}</span><span>{{$creditSupervisorFullName}}</span></td>
        <td colspan="2" class="col-md-2"><span>مدیر امور مالی :</span><span>{{ chr(10) }}</span><span>{{$financeSupervisorFullName}}</span></td>
    </tr>
    <tr>
        <td colspan="1" class="col-md-2">@if(count($storageSupervisorSignature) == 0) امضا ثبت نشده @else<img style="width: 80px;"  src="{{$storageSupervisorSignature}}">@endif</td>
        <td colspan="1" class="col-md-2">@if(count($originalJobSupervisorSignature) == 0) امضا ثبت نشده @else <img style="width: 80px;"  src="{{$originalJobSupervisorSignature}}">@endif</td>
        <td colspan="1" class="col-md-2">@if(count($bossSignature) == 0) امضا ثبت نشده @else<img style="width: 80px;"  src="{{$bossSignature}}">@endif</td>
        <td colspan="2" class="col-md-2">@if(count($creditSupervisorSignature) == 0) امضا ثبت نشده @else<img style="width: 80px;"  src="{{$creditSupervisorSignature}}">@endif</td>
        <td colspan="2" class="col-md-2">@if(count($financeSupervisorSignature) == 0) امضا ثبت نشده @else<img style="width: 80px;"  src="{{$financeSupervisorSignature}}">@endif</td>
    </tr>
    </tbody>
</table>

<h4 style="margin-top: 10px;"> « این فرم در سه نسخه تنظیم می گردد : نسخه اول حسابداری، نسخه دوم انبار، نسخه سوم تدارکات »</h4>
</div>
<div align="center">
    @if($financeSupervisorSignature == null  || $creditSupervisorSignature== null || $bossSignature == null ||  $originalJobSupervisorSignature == null || $storageSupervisorSignature == null)
        <h2 style="background-color: red; width:60%; margin: 0 auto;">بدلیل اینکه همه امضاها ثبت نشده است لذا امکان چاپ درخواست وجود ندارد ، لطفا تمامی امضاها را ثبت نمائید سپس درخواست خود را بدهید</h2>
    @else
        <button class="glyphicon glyphicon-print" style="width: 20%; font-size: 150%;" id="print" >چاپ</button>

    @endif

    <i class="fa-print"></i>
</div>
</body>
@endif

{{--@if(!empty($formContents))--}}
    {{--<body id="body">--}}
    {{--@foreach($formContents as $formContent)--}}
        {{--{!! $formContent->content  !!}--}}
        {{--<input type="hidden" id="formId" value="{{$formContent->id}}">--}}

    {{--@endforeach--}}
    {{--</body>--}}
{{--@endif--}}


