<!DOCTYPE html>
<html>
<head>
    <title>{{$pageTitle}}</title>
    <link href="{{ URL::asset('public/dashboard/css/custom-forms.css')}}" rel="stylesheet">
    <script src="{{URL::asset('public/js/jquery_v3.1.1.js')}}"></script>
    <script>
        $(document).on('click','#print',function () {

            var body      = $('#body')[0].innerHTML;
            var token     = $('#token').val();
            var requestId = $('#requestId').val();
            var formId    = $('#formId').val();
            var button    = $(this);
            $.ajax
            ({
                url  : "{{url('admin/formSave')}}/{{1}}",
                type : "post",
                context : button,
                data : {'body':body ,'_token':token , 'requestId' : requestId ,'formId':formId},
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

@if(!empty($productRequestRecords))
    <body id="body">
    <input type="hidden" id="token" value="{{ csrf_token() }}">
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
                @foreach($productRequestRecords as $productRequestRecord)
                <tr class=" padding-formTable">
                    <th class="col-md-1">{{ ++$i }}</th>
                    <th class="col-md-6">{{$productRequestRecord->title}}</th>
                    <th class="col-md-5">{{number_format($productRequestRecord->price)}}</th>
                    <input type="hidden" id="requestId" value="{{$productRequestRecord->request_id}}">
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
        <h4 class="col-md-1 text-right" dir="rtl">کارپرداز :  {{$supplierFullName}}</h4>
    </div>

    <div align="center">
        <button   style="width: 20%; font-size: 150%; margin-top: 2%;" id="print">چاپ</button>
        <i class="fa-print"></i>
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
</html>