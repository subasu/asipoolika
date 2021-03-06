@extends('layouts.formLayout')
    <title>{{$pageTitle}}</title>
    <link href="{{ URL::asset('public/dashboard/css/custom-forms.css')}}" rel="stylesheet">
<link href="{{ URL::asset('public/dashboard/css/bootstrap.min.css')}}" rel="stylesheet">
    <script src="{{URL::asset('public/js/jquery_v3.1.1.js')}}"></script>
<script src="{{URL::asset('public/dashboard/numberToLetter/num2persian.js')}}"></script>
<script src="{{URL::asset('public/dashboard/numberToLetter/num2persian.min.js')}}"></script>
    <script>
        $(document).ready(function(){
            var number=$('#number1').val();
            $('#sumToPersian').text(number.toPersian()+' ریال');
        });
        $(document).on('click','#print',function () {

           // var body      = $('#body')[0].innerHTML;
          //  var token     = $('#token').val();
            var requestId = $('#requestId').val();
            var button    = $(this);
         //   var formId    = $('#formId').val();
            var certificateId = $('#certificateId').val();
            jQuery.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax
            ({
                url  : "{{url('admin/formSave')}}/{{4}}",
                type : "post",
                context : button,
                data : {'requestId' : requestId , 'certificateId' : certificateId},
                success : function(response)
                {
                    alert(response);
                    $(button).css('display' , 'none');
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

{{--@if(!empty($certificateRecords))--}}
<body id="body">
<input type="hidden" id="token" value="{{ csrf_token() }}">
<div style="padding:1% 2.5%">
    <h5 class="text-center;" style="font-weight: bold;margin-bottom: 10px;">
        دانشگاه علوم پزشکی و خدمات بهداشتی درمانی استان اصفهان
    </h5>
    <input type="hidden" value="{{$user=\Illuminate\Support\Facades\Auth::user()}}">
    <h6 style="text-align: center">نام واحد : {{$user->unit->organization->description}}</h6>
    <h4 style="float: right;">« 8 » فرم شماره</h4>
    <h4 style="float: left;display: inline;">شماره ثبت : {{$requestId}} </h4>
    <br>
    <h4 class="text-center">« گواهی انجام خدمات »</h4>
    <h5 dir="rtl" style="text-align: justify;">بدینوسیله گواهی می شود خدمات انجام شده توسط
        شرکت/
        فروشگاه
    <span style="font-weight: bold">{{decrypt($shopComp)}}</span>
        واحد
  <span style="font-weight: bold">{{$unitName}}</span>
        به آقای/خانم
  <span style="font-weight: bold">{{$receiverFullName}}</span>
        تحویل گردید و پرداخت بلامانع است.</h5>
    <br>
    <table class="formTable col-md-12 width100 border-right" dir="rtl" style="font-size:14px;">
        <thead>
        <tr class=" padding-formTable">
            <th class="col-md-1">ردیف</th>
            <th class="col-md-4" colspan="2">شرح</th>
            <th class="col-md-2">تعداد</th>
            <th class="col-md-2"> مبلغ کل (ریال)</th>
        </tr>
        </thead>
        <tbody>
        <?php $i=0; ?>
        @foreach($certificateRecords as $certificateRecord)
        <tr>
            <td class="col-md-1">{{ ++$i }}</td>
            <td class="col-md-4" colspan="2">{{decrypt($certificateRecord->requestRecord->title)}}</td>
            <td class="col-md-2">{{$certificateRecord->count}}</td>
            <td class="col-md-3">{{number_format(decrypt($certificateRecord->price))}}</td>
            <input type="hidden" id="requestId" value="{{$requestId}}">
            <input type="hidden" id="certificateId" value="{{$certificateRecord->certificate_id}}">
        </tr>
        @endforeach
        <tr>
            <td class="col-md-4" colspan="3">جمع کل به حروف :
                <input type="hidden" value="{{$sum}}" id="number1">
                <lable id="sumToPersian"></lable></td>
            </td>
            <td class="col-md-2">جمع کل</td>
            <td class="col-md-3">{{number_format($sum)}}</td>
        </tr>
        </tbody>

    </table>
    <table class="formTable col-md-12 width100 border-right;" dir="rtl" style="font-size:14px;">
        <tr>
            <th class="col-md-3" colspan=""> دریافت کننده خدمات : {{$receiverFullName}} </th>
            <th class="col-md-3">مسئول واحد :  {{$unitSupervisorFullName}}</th>
            <th class="col-md-3">مسئول تدارکات :  {{$supplySupervisorFullName}}</th>
            <th class="col-md-3">رئیس واحد:  {{$bossFullName}}</th>
        </tr>
        <tr>
            <td class="col-md-3" colspan="">@if(strlen($receiverSignature) > 25)<img style="width: 80px;" src="{{$receiverSignature}}"> @endif @if(strlen($receiverSignature) < 25) امضا ندارد  @endif</td>
            <td class="col-md-3"><img style="width: 80px;" src="{{$unitSupervisorSignature}}"></td>
            <td class="col-md-3"><img style="width: 80px;" src="{{$supplySupervisorSignature}}"></td>
            <td class="col-md-3"><img style="width: 80px;" src="{{$bossSignature}}"></td>
        </tr>
    </table>
    <br><br><br>
    <div align="center" >
        <button   style="margin-top:2%;width: 20%; font-size: 150%;" id="print">چاپ</button>
        <i class="fa-print"></i>
    </div>
</div>

</body>
{{--@endif--}}

{{--@if(!empty($formExistence))--}}
    {{--<body id="body">--}}
    {{--@foreach($formExistence as $form)--}}
        {{--{!! $form->content  !!}--}}
        {{--<input type="hidden" id="formId" value="{{$form->id}}">--}}
    {{--@endforeach--}}
    {{--</body>--}}
{{--@endif--}}
