<!DOCTYPE html>
<html>
<head>
    <link href="{{ URL::asset('public/dashboard/css/custom-forms.css')}}" rel="stylesheet">
    <link href="{{ url('public/dashboard/css/bootstrap.min.css')}}" rel="stylesheet">
    <script src="{{URL::asset('public/js/jquery_v3.1.1.js')}}"></script>
    <script>
        $(document).on('click','#print',function () {

            var body = $('#body')[0].innerHTML;
            var token = $('#token').val();
            var requestId = $('#requestId').val();
            $.ajax
            ({
                url  : "{{url('admin/formSave')}}",
                type : "post",
                data : {'body':body ,'_token':token , 'requestId' : requestId},
                success : function(response)
                {
                    alert(response);
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
<body id="body">
<input type="hidden" id="token" value="{{ csrf_token() }}">
<div style="padding:1% 2.5%">
    <h4 class="text-center">
        دانشگاه علوم پزشکی و خدمات بهداشتی و درمانی استان اصفهان
    </h4>
    <h4> نام واحد : {{$unitName}}  </h4>
    <h4 style="float: right;">فرم شماره 8</h4>
    <h4 style="float: left;display: inline;"> شماره ثبت: {{$requestId}} </h4>
    <br>
    <h4 class="text-center">« گواهی انجام خدمات »</h4>
    <h5 dir="rtl" style="text-align: justify;">بدینوسیله گواهی می شود خدمات انجام شده به شرح زیر توسط
        شرکت/
        فروشگاه
        {{$shopComp}}
        جهت واحد
        {{$unitName}}
        به آقای/خانم
        {{$receiverFullName}}
        تحویل گردید و پرداخت بلامانع است.</h5>
    <br>
    <table class="formTable col-md-12 width100 border-right" dir="rtl">
        <thead>
        <tr class=" padding-formTable">
            <th class="col-md-1">ردیف</th>
            <th class="col-md-4" colspan="2">شرح</th>
            <th class="col-md-2">تعداد</th>
            <th class="col-md-2"> مبلغ کل (تومان)</th>
        </tr>
        </thead>
        <tbody>
        <?php $i=0; ?>
        @foreach($certificateRecords as $certificateRecord)
        <tr>
            <td class="col-md-1">{{ ++$i }}</td>
            <td class="col-md-4" colspan="2">{{$certificateRecord->requestRecord->title}}</td>
            <td class="col-md-2">{{$certificateRecord->count}}</td>
            <td class="col-md-3">{{number_format($certificateRecord->price)}}</td>
            <input type="hidden" id="requestId" value="{{$requestId}}">
        </tr>
        @endforeach
        <tr>
            <td class="col-md-4" colspan="3">جمع کل به حروف</td>
            <td class="col-md-2">جمع کل</td>
            <td class="col-md-3">{{number_format($sum)}}</td>
        </tr>
        </tbody>

    </table>
    <table class="formTable col-md-12 width100 border-right " dir="rtl">
        <tr>
            <th class="col-md-3" colspan=""> دریافت کننده خدمات : {{$receiverFullName}} </th>
            <th class="col-md-3">مسئول واحد :  {{$unitSupervisorFullName}}</th>
            <th class="col-md-3">مسئول تدارکات :  {{$supplySupervisorFullName}}</th>
            <th class="col-md-3">رئیس واحد:  {{$bossFullName}}</th>
        </tr>
        <tr>
            <td class="col-md-3" colspan="">@if(count($receiverSignature) > 22)<img src="{{$receiverSignature}}"> @endif @if(count($receiverSignature) <= 22)امضا ندارد @endif</td>
            <td class="col-md-3"><img style="width: 100px; height: 100px;" src="{{$unitSupervisorSignature}}"></td>
            <td class="col-md-3"><img style="width: 100px; height: 100px;" src="{{$supplySupervisorSignature}}"></td>
            <td class="col-md-3"><img style="width: 100px; height: 100px;" src="{{$bossSignature}}"></td>
        </tr>
    </table>
    <br><br><br>
    <div align="center">
        <button   style="width: 20%; font-size: 150%;" id="print">چاپ</button>
        <i class="fa-print"></i>
    </div>
</div>

</body>
</html>