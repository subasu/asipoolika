@extends('layouts.formLayout')

    @if(!empty($certificateRecord[0]))
        @if($certificateRecords[0]->certificate->certificate_type_id == 1)
            <title>{{$pageTitleInstall}}</title>
        @endif
        @if($certificateRecords[0]->certificate->certificate_type_id == 2)
            <title>{{$pageTitleUse}}</title>
        @endif
    @endif

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
            var body      = $('#body')[0].innerHTML;
            //var token     = $('#token').val();
            var requestId = $('#requestId').val();
            var button    = $(this);
            var certificateId = $('#certificateId').val();
            var title         = $('#title').val();
            jQuery.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax
            ({
               url  : "{{url('admin/formSave')}}/{{3}}",
               type : "post",
               context :{'button':button,'body':body},
               data : {'requestId':requestId , 'certificateId' : certificateId ,'title':title},
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
        });
//        function printData(){
//            var printContents = $('#body')[0].innerHTML;
//            w = window.open();
//            w.document.write(printContents);
//            w.document.close(); // necessary for IE >= 10
//            w.focus(); // necessary for IE >= 10
//            setTimeout(function () { // necessary for Chrome
//                w.print();
//                w.close();
//            }, 3000);
//            return true;
//        }

    </script>
<style>
    *{font-size: 12px;}
</style>
{{--@if(!empty($certificateRecords))--}}
    <body id="body">
    <input type="hidden" id="token" value="{{ csrf_token() }}">
    <div style="padding:1% 2.5%">
        <h4 class="text-center">
            دانشگاه علوم پزشکی و خدمات بهداشتی درمانی استان اصفهان
        </h4>
        <input type="hidden" value="{{$user=\Illuminate\Support\Facades\Auth::user()}}">
        <h6 style="text-align: center">نام واحد : {{$user->unit->organization->description}}</h6>
        <div class="row" style="text-align: left">
                <div class="col-md-3  push-left">
                    <div class="row">
                        <div class="remove-border col-md-6">تاریخ : {{$date}}</div>
                    </div>
                    <div class="row">
                        <div class="remove-border col-md-6 ">شماره : {{$certificateId}}</div>
                    </div>
                </div>
        </div>

    @if(!empty($certificateRecords[0]))
        @if($certificateRecords[0]->certificate->certificate_type_id == 1)
            <h4 class="text-center">« صورتجلسه تحویل و نصب »</h4>
            <input type="hidden" id="title" value="گواهی تحویل و نصب کالا">
        @endif
        @if($certificateRecords[0]->certificate->certificate_type_id == 2)
            <h4 class="text-center">« صورتجلسه تحویل و مصرف »</h4>
                <input type="hidden" id="title" value="گواهی تحویل و مصرف کالا">
        @endif
        <br>
        @if($certificateRecords[0]->certificate->certificate_type_id == 1)
                <h4 dir="rtl" style="text-align: justify;font-size:12px;">بدینوسیله گواهی می شود خدمات انجام شده توسط
                شرکت/
                فروشگاه
    <span style="font-weight: bold">{{$shopComp}}</span>
                واحد
    <span style="font-weight: bold">{{$unitName}}</span>
                به آقای/خانم
 <span style="font-weight: bold">{{$receiverName .chr(10). $receiverFamily}}</span>
                تحویل گردید و پرداخت شده است.</h4>
        @endif
        @if($certificateRecords[0]->certificate->certificate_type_id == 2)
                <h4 dir="rtl" style="text-align: justify;font-size:12px;">بدینوسیله گواهی می شود خدمات انجام شده توسط
                شرکت/
                فروشگاه
                <span style="font-weight: bold">{{$shopComp}}</span>
                واحد
                <span style="font-weight: bold">{{$unitName}}</span>
                به آقای/خانم
                <span style="font-weight: bold">{{$receiverName .chr(10). $receiverFamily}}</span>
                تحویل گردید و پرداخت بلامانع است.</h4>
        @endif
    @endif
    <br>
         <table class="formTable text-center width100 col-md-12 col-sm-12 col-xs-12" dir="rtl" style="font-size:12px;">
             <thead>
             <tr class="">
                 <th class="col-md-2" colspan="2">عنوان</th>
                 <th class="col-md-2">تعداد</th>
                 <th class="col-md-2"> مبلغ کل (ریال)</th>
             </tr>
             </thead>
             <tbody>
             @foreach($certificateRecords as $certificateRecord)
                 <tr>
                     <td class="col-md-2" colspan="2">{{decrypt($certificateRecord->requestRecord->title)}}</td>
                     <td class="col-md-2">{{$certificateRecord->count}}</td>
                     <td class="col-md-2">{{number_format($certificateRecord->price)}}</td>
                     <input type="hidden" id="requestId" value="{{$certificateRecord->certificate->request_id}}">
                     <input type="hidden" id="certificateId" value="{{$certificateRecord->certificate_id}}">
                 </tr>
             @endforeach
             <tr>
                 <td class="col-md-4" colspan="2">جمع کل به حروف :
                     <input type="hidden" value="{{$sum}}" id="number1">
                     <lable id="sumToPersian" ></lable></td>
                 <td class="col-md-2">جمع کل به عدد</td>
                 <td class="col-md-3">{{number_format($sum)}}</td>

             </tr>
             <tr style="font-size:12px;font-weight: lighter;">
                 <th class="col-md-2" style="line-height: 15px;padding:5px 2px 5px 2px">تحویل گیرنده : {{$receiverFullName}}</th>
                 <th class="col-md-2" style="line-height: 15px;"> مسئول واحد : {{$unitSupervisorFullName}} </th>
                 <th class="col-md-2" style="line-height: 15px;">کارپرداز : {{$supplierFullName}}</th>
                 <th class="col-md-2" style="line-height: 15px;">  رئیس واحد : {{$bossFullName}} </th>
             </tr>
             <tr >
                 <td class="col-md-2">@if(strlen($receiverSignature) > 25)<img style="height: 80px;" src="{{$receiverSignature}}"> @endif @if(strlen($receiverSignature) < 25) امضا ندارد  @endif</td>
                 <td class="col-md-2"><img style="height: 80px;" src="{{$unitSupervisorSignature}}"></td>
                 <td class="col-md-2">@if(strlen($supplierSignature) > 25)<img style="height: 80px;" src="{{$supplierSignature}}"> @endif @if(strlen($supplierSignature) < 25)امضا ندارد @endif</td>
                 <td class="col-md-2"><img style="height: 80px;" src="{{$bossSignature}}"></td>
             </tr>
             </tbody>

         </table>
        <br><br>
        <div align="center">
            <button style="width: 20%; font-size: 150%;margin-top:10px;" id="print">چاپ</button>
            <i class="fa-print"></i>
        </div>
    </div>
    </body>
{{--@endif--}}

{{--@if(!empty($oldCertificates))--}}

    {{--<body id="body">--}}
    {{--@foreach($oldCertificates as $oldCertificate)--}}
        {{--{!! $oldCertificate->content  !!}--}}
        {{--<input type="hidden" id="formId" value="{{$formContent->id}}">--}}

    {{--@endforeach--}}

    {{--</body>--}}
{{--@endif--}}

