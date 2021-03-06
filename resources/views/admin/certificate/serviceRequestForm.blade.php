@extends('layouts.formLayout')

    <title>{{$pageTitle}}</title>
    <link href="{{ url('public/dashboard/css/custom-forms.css')}}" rel="stylesheet">
    <link href="{{ url('public/dashboard/css/bootstrap.min.css')}}" rel="stylesheet">
    <script src="{{URL::asset('public/js/jquery_v3.1.1.js')}}"></script>
    <script>
        $(document).ready(function(){
            var number=$('#number1').val();
            $('#sumToPersian').text(number.toPersian()+' ریال');
        });
        $(document).on('click','#print',function () {

            //var body      = $('#body')[0].innerHTML;
          //  var token     = $('#token').val();
            var requestId = $('#requestId').val();
            var button    = $(this);
         //   var formId    = $('#formId').val();
            jQuery.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax
            ({
                url  : "{{url('admin/formSave')}}/{{2}}",
                type : "post",
                data : {'requestId' : requestId },
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
<div style="padding:1% 2%">
    <h5 style="text-align: center;padding: 0;">دانشگاه علوم پزشکی و خدمات بهداشتی درمانی استان اصفهان</h5>
    <input type="hidden" value="{{$user=\Illuminate\Support\Facades\Auth::user()}}">
    <h6 style="text-align: center">نام واحد : {{$user->unit->organization->description}}</h6>
    <div class="row" style="text-align: left">
        <div class="col-md-3  push-left">
            <div class="row">
                <div class="remove-border col-md-6">تاریخ : {{$request->date}}</div>
            </div>
            <div class="row">
                <div class="remove-border col-md-6 ">شماره : {{$request[0]->id}}</div>
            </div>
        </div>
    </div>
    <br>
    <h5 style="padding-bottom: 1%"> « درخواست خرید خدمت »</h5>
    <table style="font-size: 10px;" cellpadding="0" cellspacing="0" class="formTable col-md-12 text-center width100" dir="rtl" >
        <thead>
        <tr style="border-bottom: 0 solid #000;">
            <th colspan="3" class="col-md-3" style="line-height: 15px;">واحد اقدام کننده :</th>
            <th colspan="6" class="col-md-5">تدارکات</th>
            <th colspan="4" class="col-md-4">درخواست متقاضی</th>
        </tr>
        <tr>
            <th colspan="3" class="col-md-3" style="line-height: 15px;">واحد متقاضی / پروژه : </th>
            <th colspan="6" class="col-md-5">{{$unitName}}</th>
            <th colspan="1" class="col-md-1">شماره : </th>
            <th colspan="1" class="col-md-1">{{$requestNumber}}</th>
            <th colspan="1" class="col-md-1"  style="line-height: 15px;">تاریخ:</th>
            <th colspan="1" class="col-md-1">{{$date}}</th>
        </tr>

        <tr style="border-bottom: 0;">
            <th rowspan="2" colspan="1" class="col-md-1">ردیف</th>
            <th rowspan="2" colspan="7" class="col-md-6">خدمت</th>
            <th rowspan="2" colspan="1" class="col-md-1" style="line-height: 15px;">مقدار / برآورد مقدار</th>
            <th colspan="4" class="col-md-4"> برآورد قیمت - ریال</th>
        </tr>
        <tr>
            <th colspan="2" class="col-md-2" style="border-top: 0;">نرخ</th>
            <th colspan="2" class="col-md-2" style="border-top: 0;">مبلغ</th>
        </tr>
        </thead>
        <tbody>

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

            <tr>
                {{--<td colspan="11" class="col-md-10" > <span style="margin-right: 82%;">جمع</span>--}}
                    {{--<input type="hidden" value="{{$sum}}" id="number1">--}}
                    {{--<br><p id="sumToPersian" style="font-size:15px;color:black;"></p>--}}
                {{--</td>--}}
                <td colspan="11" style="text-align: left;">جمع :
                    <input type="hidden" value="{{$sum}}" id="number1">
                    <label id="sumToPersian" ></label></td>
                <td colspan="6" style="padding-right: 10px;padding-left: 20px;">{{number_format($sum)}}
                {{--<td colspan="2" class="col-md-2">{{number_format($sum)}}--}}

                {{--</td>--}}
            </tr>
            <tr>
                <td colspan="3" class="col-md-2" style="line-height: 15px;padding:5px 2px 5px 2px"><span>مسئول واحد:</span><span>{{ chr(10)  }} </span>{{$unitSupervisorFullName}}</td>
                <td colspan="3" class="col-md-3" style="line-height: 15px;"><span>تامین کننده:</span><span>{{ chr(10)  }} </span>{{$supplySupervisorFullName}}</td>
                <td colspan="3" class="col-md-3" style="line-height: 15px;"><span>تصویب کننده:</span><span>{{ chr(10)  }} </span>{{$bossFullName}}</td>
                <td colspan="2" class="col-md-3" style="line-height: 15px;"><span>مسئول اعتبار:</span><span>{{ chr(10)  }} </span>{{$creditSupervisorFullName}}</td>
                <td colspan="3" class="col-md-3" style="line-height: 15px;"><span>مدیر امور مالی:</span><span>{{ chr(10)  }} </span>{{$financeSupervisorFullName}}</td>
            </tr>
            <tr>
                <td colspan="3" class="col-md-2">@if(count($unitSupervisorSignature) == 0) امضا ثبت نشده @else   <img style="width: 80px;" src="{{$unitSupervisorSignature}}"> @endif</td>
                <td colspan="3" class="col-md-2"> @if(count($supplySupervisorSignature) == 0) امضا ثبت نشده @else <img style="width: 80px;" src="{{$supplySupervisorSignature}}"> @endif</td>
                <td colspan="3" class="col-md-2">@if(count($bossSignature) == 0) امضا ثبت نشده @else <img style="width: 80px;" src="{{$bossSignature}}">@endif</td>
                <td colspan="2" class="col-md-2">@if(count($creditSupervisorSignature) == 0) امضا ثبت نشده @else <img style="width: 80px;" src="{{$creditSupervisorSignature}}"> @endif</td>
                <td colspan="3" class="col-md-2">@if(count($financeSupervisorSignature) == 0) امضا ثبت نشده @else <img style="width: 80px;" src="{{$financeSupervisorSignature}}"> @endif</td>
            </tr>

        </tbody>
    </table>
    <h4></h4>
</div>
<div align="center">
        @if($unitSupervisorSignature == null  || $supplySupervisorSignature== null || $bossSignature == null ||  $creditSupervisorSignature == null || $financeSupervisorSignature == null)
            <h3 style="background-color: red; width:60%; margin-top:35%;">بدلیل اینکه همه امضاها ثبت نشده است لذا امکان چاپ درخواست وجود ندارد ، لطفا تمامی امضاها را ثبت نمائید سپس درخواست خود را بدهید</h3>
        @else
            <button  style="width: 20%; font-size: 150%; margin-top:10px;" id="print" >چاپ</button>
        @endif
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
