@extends('layouts.formLayout')

    <title>{{$pageTitle}}</title>
    <link href="{{ URL::asset('public/dashboard/css/custom-forms.css')}}" rel="stylesheet">
    <script src="{{URL::asset('public/js/jquery_v3.1.1.js')}}"></script>
    <script>
        $(document).on('click','#print',function () {

           // var body      = $('#body')[0].innerHTML;
            //var token     = $('#token').val();
            var requestId = $('#requestId').val();
           // var formId    = $('#formId').val();
            var button    = $(this);
            jQuery.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax
            ({
                url  : "{{url('admin/formSave')}}/{{1}}",
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


{{--@if(!empty($productRequestRecords))--}}
    <body id="body">

    <div style="padding:1% 2.5%">
        <h4 class="text-center">
            خلاصه ی تنظیمی فکتور های خریداری شده جهت شبکه بهداشت و درمان خمینی شهر </h4><br>
        <table class="formTable col-md-12 width100 border-right" dir="rtl">
            <thead>
            <tr class=" padding-formTable">
                <th class="col-md-1">ردیف</th>
                <th class="col-md-6">عنوان</th>
                <th class="col-md-5">مبلغ به تومان</th>
            </tr>
                <?php $i=0; ?>
                @foreach($bills as $bill)
                <tr class=" padding-formTable">
                    <th class="col-md-1">{{ ++$i }}</th>
                    <th class="col-md-6">فاکتور شماره : {{$bill->factor_number}}</th>
                    <th class="col-md-5">{{number_format($bill->final_price)}}</th>
                    <input type="hidden" id="requestId" value="{{$bill->request_id}}">
                 </tr>
                @endforeach
            </thead>
            <tbody>
            <tr>
                <th class="col-md-1" style="background-color: lightgray;"></th>
                <th class="col-md-6">جمع کل</th>
                    <th class="col-md-5">{{number_format($sum)}}</th>

            </tr>
            </tbody>

        </table>
        <br>
        <h4 class="col-md-1 text-right" dir="rtl" style="margin-right: 2%;">نام کارپرداز :   {{$supplierFullName}}</h4>
        <h4 class="col-md-1 text-left" dir="rtl" style="margin-left: 10%; margin-top: -1.75%;"> امضاء کار پرداز: </h4>
        <img src="{{$supplierSignature}}" style="width: 100px; height: 100px;">

    </div>

    <div align="center">
        <button   style="width: 20%; font-size: 150%; margin-top: 2%;" id="print">چاپ</button>
        <i class="fa-print"></i>
    </div>
    </body>
{{--@endif--}}

{{--@if(!empty($formContents))--}}
    {{--<body id="body">--}}
        {{--@foreach($formContents as $formContent)--}}
            {{--{!! $formContent->content  !!}--}}
            {{--<input type="hidden" id="formId" value="{{$formContent->id}}">--}}
        {{--@endforeach--}}
    {{--</body>--}}
{{--@endif--}}
