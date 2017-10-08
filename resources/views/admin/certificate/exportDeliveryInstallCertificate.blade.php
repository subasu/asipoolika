<!DOCTYPE html>
<html>
<head>
    <title>{{$pageTitle}}</title>
    <link href="{{ URL::asset('public/dashboard/css/custom-forms.css')}}" rel="stylesheet">
    <script src="{{URL::asset('public/js/jquery_v3.1.1.js')}}"></script>
    <script>
        $(document).on('click','#print',function () {

            var body = $('#body')[0].innerHTML;
            var token = $('#token').val();
            var requestId = $('#requestId').val();
            var button    = $(this);
            var certificateId = $('#certificateId').val();
            $.ajax
            ({
               url  : "{{url('admin/formSave')}}/{{3}}",
               type : "post",
               context : button,
               data : {'body':body ,'_token':token,'requestId':requestId , 'certificateId' : certificateId},
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
</head>
@if(!empty($certificateRecords))
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
    <br>
    <h3 class="text-center">« صورت جلسه تحویل کالا و نصب »</h3><br>
    <h4 dir="rtl" style="text-align: justify;">بدینوسیله گواهی می شود خدمات انجام شده به شرح زیر توسط
        شرکت/
        فروشگاه
        {{$shopComp}}
        جهت واحد
        {{$unitName}}
        به آقای/خانم
        {{$receiverName .chr(10). $receiverFamily}}
        تحویل گردید و پرداخت شده است.</h4>
    <br>
    <table class="formTable col-md-12 width100 border-right" dir="rtl">
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
            <td class="col-md-4" colspan="2">{{$certificateRecord->requestRecord->title}}</td>
            <td class="col-md-2">{{$certificateRecord->count}}</td>
            <td class="col-md-3">{{number_format($certificateRecord->price)}}</td>
            <input type="hidden" id="requestId" value="{{$certificateRecord->certificate->request_id}}">
            <input type="hidden" id="certificateId" value="{{$certificateRecord->certificate_id}}">
        </tr>
        @endforeach
        <tr>
            <td class="col-md-4" colspan="2">جمع کل به حروف</td>
            <td class="col-md-2">جمع کل به عدد</td>
            <td class="col-md-3">{{number_format($sum)}}</td>
        </tr>
        <tr>
            <th class="col-md-3">تحویل گیرنده:   {{$receiverFullName}}</th>
            <th class="col-md-3"> مسئول واحد : {{$unitSupervisorFullName}} </th>
            <th class="col-md-3">کارپرداز :{{$supplierFullName}}</th>
            <th class="col-md-3">  رئیس واحد : {{$bossFullName}} </th>
        </tr>
        <tr>
            <td class="col-md-3"><img style="height: 100px; width: 100px;" src="{{$receiverSignature}}"></td>
            <td class="col-md-3"><img style="height: 100px; width: 100px;" src="{{$unitSupervisorSignature}}"></td>
            <td class="col-md-3">@if(count($supplierSignature) > 22)<img src="{{$supplierSignature}}"> @endif @if(count($supplierSignature) <= 22)امضا ندارد @endif</td>
            <td class="col-md-3"><img style="height: 100px; width: 100px;" src="{{$bossSignature}}"></td>
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
@endif

@if(!empty($oldCertificates))
    <body id="body">
    @foreach($oldCertificates as $oldCertificate)
        {!! $oldCertificate->content  !!}
        {{--<input type="hidden" id="formId" value="{{$formContent->id}}">--}}
    @endforeach
    </body>
@endif

</html>