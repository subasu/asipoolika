@extends('layouts.formLayout')

    <title>{{$pageTitle}}</title>
    <link href="{{ url('public/dashboard/css/custom-forms.css')}}" rel="stylesheet">
    <link href="{{ url('public/dashboard/css/bootstrap.min.css')}}" rel="stylesheet">
    <script src="{{URL::asset('public/js/jquery_v3.1.1.js')}}"></script>
    <script>
        $(document).on('click','#print',function () {

            var body      = $('#body')[0].innerHTML;
          //  var token     = $('#token').val();
            var requestId = $('#requestId').val();
            var button    = $(this);
            var formId    = $('#formId').val();
            jQuery.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax
            ({
                url  : "{{url('admin/formSave')}}/{{2}}",
                type : "post",
                data : {'body':body  , 'requestId' : requestId , 'formId' : formId},
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

@if(!empty($productRequestRecords))
<body id="body">
<input type="hidden" id="token" value="{{ csrf_token() }}">
<div style="padding:1% 0.5%">
    <h5 style="text-align: center;padding: 0;">دانشگاه علوم پزشکی و خدمات درمانی استان اصفهان</h5>
    <h6 class="text-center">
        نام واحد: شبکه بهداشت و درمان شهرستان خمینی شهر
    </h6>
    <br>
    <h5 style="padding-bottom: 1%"> « درخواست خرید خدمت »</h5>
    <table cellpadding="0" cellspacing="0" class="formTable col-md-12 text-center width100" dir="rtl" >
        <thead>

        <tr style="border-bottom: 0 solid #000;">
            <th colspan="3" class="col-md-3">واحد اقدام کننده :</th>
            <th colspan="6" class="col-md-5">تدارکات</th>
            <th colspan="4" class="col-md-4">درخواست متقاضی</th>
        </tr>
        <tr>
            <th colspan="3" class="col-md-3">واحد متقاضی :</th>
            <th colspan="6" class="col-md-5">{{$unitName}}</th>
            <th colspan="1" class="col-md-1">شماره</th>
            <th colspan="1" class="col-md-1">{{$requestNumber}}</th>
            <th colspan="1" class="col-md-1">تاریخ</th>
            <th colspan="1" class="col-md-1">{{$date}}</th>
        </tr>

        <tr style="border-bottom: 0;">
            <th rowspan="2" colspan="1" class="col-md-1">ردیف</th>
            <th rowspan="2" colspan="7" class="col-md-6">خدمت</th>
            <th rowspan="2" colspan="1" class="col-md-1">مقدار / برآورد مقدار</th>
            <th colspan="4" class="col-md-4">برآورد قیمت - تومان</th>
        </tr>
        <tr>
            <th colspan="2" class="col-md-2" style="border-top: 0;">نرخ</th>
            <th colspan="2" class="col-md-2" style="border-top: 0;">مبلغ</th>
        </tr>
        </thead>
        <tbody>
         {{--dynamic tr start--}}
         <?php $i=0; ?>
         @foreach($productRequestRecords as $productRequestRecord)
            <tr>
                <td colspan="1" class="col-md-1">{{++$i}}</td>
                <td colspan="7" class="col-md-5">{{$productRequestRecord->title}}</td>
                <td colspan="1" class="col-md-2">{{$productRequestRecord->count .chr(10). $productRequestRecord->unit_count}}</td>
                <td colspan="2" class="col-md-2">{{number_format($productRequestRecord->rate)}}</td>
                <td colspan="2" class="col-md-2">{{number_format($productRequestRecord->price)}}</td>
                <input type="hidden" id="requestId" value="{{$productRequestRecord->request_id}}">
            </tr>
          @endforeach
             {{--dynamic tr end--}}
            <tr>
                <td colspan="11" class="col-md-10" > <span style="margin-right: 82%;">جمع</span> </td>
                <td colspan="2" class="col-md-2">{{number_format($sum)}}</td>
            </tr>
            <tr>
                <td colspan="3" class="col-md-2"><span>مسئول واحد:</span><span>{{ chr(10)  }} </span>{{$unitSupervisorFullName}}</td>
                <td colspan="3" class="col-md-3"><span>تامین کننده:</span><span>{{ chr(10)  }} </span>{{$supplySupervisorFullName}}</td>
                <td colspan="3" class="col-md-3"><span>تصویب کننده:</span><span>{{ chr(10)  }} </span>{{$bossFullName}}</td>
                <td colspan="2" class="col-md-3"><span>مسئول اعتبار:</span><span>{{ chr(10)  }} </span>{{$creditSupervisorFullName}}</td>
                <td colspan="3" class="col-md-3"><span>مدیر امور مالی:</span><span>{{ chr(10)  }} </span>{{$financeSupervisorFullName}}</td>
            </tr>
            <tr>
                <td colspan="3" class="col-md-2"><img style="width: 100px; height: 100px;" src="{{$unitSupervisorSignature}}"></td>
                <td colspan="3" class="col-md-2"><img style="width: 100px; height: 100px;" src="{{$supplySupervisorSignature}}"></td>
                <td colspan="3" class="col-md-2"><img style="width: 100px; height: 100px;" src="{{$bossSignature}}"></td>
                <td colspan="2" class="col-md-2"><img style="width: 100px; height: 100px;" src="{{$creditSupervisorSignature}}"></td>
                <td colspan="3" class="col-md-2"><img style="width: 100px; height: 100px;" src="{{$financeSupervisorSignature}}"></td>

            </tr>

        </tbody>
    </table>
    <br><br><br>
    <div align="center">
        <button   style="width: 20%; font-size: 150%; margin-top: 2%;" id="print">چاپ</button>
        <i class="fa-print"></i>
    </div>
</div>

</body>
@endif

@if(!empty($formContents))
    <body id="body">
    @foreach($formContents as $formContent)
        {!! $formContent->content  !!}
        <input type="hidden" id="formId" value="{{$formContent->id}}">
    @endforeach
    </body>
@endif
