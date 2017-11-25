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

{{--@if(!empty($certificateRecords))--}}
    <body id="body">
    <input type="hidden" id="token" value="{{ csrf_token() }}">
    <div style="padding:1% 2.5%">
        <h3 class="text-center">
            دانشگاه علوم پزشکی و خدمات بهداشتی و درمانی استان اصفهان
        </h3><br>
        <div class="row">
            <div class="container">
                <table class="col-md-12 " dir="rtl">
                    <tr class="col-md-12">
                        <th class="remove-border col-md-6 ">تاریخ :</th>
                        <th class="remove-border col-md-6 ">{{$date}}</th>
                    </tr>
                    <tr>
                        <th class="remove-border col-md-6 ">شماره :</th>
                        <th class="remove-border col-md-6 ">{{$certificateId}}</th>
                    </tr>
                </table>
            </div>
        </div>

    @if(!empty($certificateRecords[0]))
        @if($certificateRecords[0]->certificate->certificate_type_id == 1)
            <h3 class="text-center">« صورت جلسه تحویل و نصب »</h3>
            <input type="hidden" id="title" value="گواهی تحویل و نصب کالا">
        @endif
        @if($certificateRecords[0]->certificate->certificate_type_id == 2)
            <h3 class="text-center">« صورت جلسه تحویل و مصرف »</h3>
                <input type="hidden" id="title" value="گواهی تحویل و مصرف کالا">
        @endif
        <br>
        @if($certificateRecords[0]->certificate->certificate_type_id == 1)
            <h4 dir="rtl" style="text-align: justify;">بدینوسیله گواهی می شود خدمات انجام شده به شرح زیر توسط
                شرکت/
                فروشگاه
                {{$shopComp}}
                جهت واحد
                {{$unitName}}
                به آقای/خانم
                {{$receiverName .chr(10). $receiverFamily}}
                تحویل گردید و پرداخت شده است.</h4>
        @endif
        @if($certificateRecords[0]->certificate->certificate_type_id == 2)
            <h4 dir="rtl" style="text-align: justify;">بدینوسیله گواهی می شود خدمات انجام شده به شرح زیر توسط
                شرکت/
                فروشگاه
                {{$shopComp}}
                جهت واحد
                {{$unitName}}
                به آقای/خانم
                {{$receiverName .chr(10). $receiverFamily}}
                تحویل گردید و پرداخت بلامانع است.</h4>
        @endif
    @endif
    <br>
    <table class="formTable col-md-12 width100 border-right" dir="rtl" style="font-size: 14px;">
        <thead>
        <tr class=" padding-formTable">
            <th class="col-md-4" colspan="2">عنوان</th>
            <th class="col-md-2">تعداد</th>
            <th class="col-md-3"> مبلغ کل (ریال)</th>
        </tr>
        </thead>
        <tbody>
        @foreach($certificateRecords as $certificateRecord)
        <tr>
            <td class="col-md-4" colspan="2">{{decrypt($certificateRecord->requestRecord->title)}}</td>
            <td class="col-md-2">{{$certificateRecord->count}}</td>
            <td class="col-md-3">{{number_format($certificateRecord->price)}}</td>
            <input type="hidden" id="requestId" value="{{$certificateRecord->certificate->request_id}}">
            <input type="hidden" id="certificateId" value="{{$certificateRecord->certificate_id}}">
        </tr>
        @endforeach
        <tr>
            <td class="col-md-4" colspan="2">جمع کل به حروف :
                <input type="hidden" value="{{$sum}}" id="number1">
                <lable id="sumToPersian"  class="label label-default"></lable></td>
            <td class="col-md-2">جمع کل به عدد</td>
            <td class="col-md-3">{{number_format($sum)}}</td>

        </tr>
        <tr>
            <th class="col-md-2">تحویل گیرنده:   {{$receiverFullName}}</th>
            <th class="col-md-2"> مسئول واحد : {{$unitSupervisorFullName}} </th>
            <th class="col-md-2">کارپرداز :{{$supplierFullName}}</th>
            <th class="col-md-2">  رئیس واحد : {{$bossFullName}} </th>
        </tr>
        <tr>
            <td class="col-md-2">@if(strlen($receiverSignature) > 25)<img style="height: 80px;" src="{{$receiverSignature}}"> @endif @if(strlen($receiverSignature) < 25) امضا ندارد  @endif</td>
            <td class="col-md-2"><img style="height: 80px;" src="{{$unitSupervisorSignature}}"></td>
            <td class="col-md-2">@if(strlen($supplierSignature) > 25)<img style="height: 80px;" src="{{$supplierSignature}}"> @endif @if(strlen($supplierSignature) < 25)امضا ندارد @endif</td>
            <td class="col-md-2"><img style="height: 80px;" src="{{$bossSignature}}"></td>
        </tr>
        </tbody>




        </table>
        <br><br><br>
        <div align="center">
            <button  class="glyphicon glyphicon-print" style="width: 20%; font-size: 150%;" id="print">چاپ</button>
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

