@extends('layouts.adminLayout')
@section('content')

<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title" style="direction: rtl">
                @if(!empty($certificateRecords[0]))
                    <input type="hidden" value="{{$certificateRecords[0]->id}}" name="certificate_id">
{{--                    <input type="hidden" value="{{$user->unit->title}}" content="{{$user->is_supervisor}}" name="user_unit_title" id="user_unit_title">--}}
                    <h2><i class="fa fa-list"></i> لیست رکوردهای گواهی شماره : {{$certificateRecords[0]->certificate_id}} | ثبت شده توسط :   {{$certificateRecords[0]->certificate->request->user->name}} {{$certificateRecords[0]->certificate->request->user->family}} از واحد {{$certificateRecords[0]->certificate->request->user->unit->title}} | <span style="color: tomato;font-weight: bold">تعداد رکوردها : {{$certificateRecords->count()}} رکورد</span></h2>
                @endif
                {{--<h2>لیست رکوردهای درخواست کالای شماره : {{$requestRecords[0]->request_id}}</h2>--}}
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link" data-toggle="tooltip" title="جمع کردن"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li><a class="close-link" data-toggle="tooltip" title="بستن"><i class="fa fa-close"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <table style="direction:rtl;text-align: center;font-size: 16px;" id="table" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th style="text-align: center ;">شناسه</th>
                        <th style="text-align: center ;">تامین کننده</th>
                        <th style="text-align: center ;">شرح</th>
                        <th style="text-align: center ;">مقدار</th>
                        {{--                        @if($user->unit->title=='تدارکات')--}}
                        <th style="text-align: center ;">نرخ</th>
                        {{--@endif--}}
                        <th style="text-align: center ;">قیمت</th>
                        <th class="col-md-3" style="text-align: center ;">عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    {{--<form id="serviceDetailForm">--}}
                    {{ csrf_field() }}
                    <input type="hidden" id="token" name="csrf-token" value="{{ csrf_token() }}">
                    @foreach($certificateRecords as $certificateRecord)
                        <tr>
                            <input type="hidden" value="{{$certificateRecord->id}}" class="record_id">
                            <th style="text-align: center">{{$certificateRecord->id}}</th>
                            <td>{{$certificateRecord->certificate->shop_comp}}</td>
                            <td>{{$certificateRecord->certificate_record_title}}</td>
                            <td id="count" content="{{$certificateRecord->count}}">{{$certificateRecord->count}} {{$certificateRecord->unit_count}}</td>
                            {{--<input type="hidden" class="count" value="{{$requestRecord->count}}" name="count">--}}
                            {{--<input type="hidden" class="" value="2000" name="count[]">--}}
                            <td>{{number_format($certificateRecord->rate)}} تومان</td>
                            <td>{{number_format($certificateRecord->price)}} تومان</td>
                            <td>
                            {{--<button class="btn btn-link btn-round" data-toggle="tooltip" title="{{$requestRecord->description}}"> توضیحات--}}
                                {{--</button>--}}
                                <button id="acceptRequest" content="{{$certificateRecord->certificate_id}}" name="{{$certificateRecord->id}}" type="button" class="btn btn-success col-md-12" >تایید</button>
                            </td>
                            {{--<input id="refuseRequest" content="{{$requestRecord->id}}" name="{{$requestRecord->request_id}}" type="button" class="btn btn-danger"  required value="رد کردن" /></td>--}}
                        </tr>
                    @endforeach
                    {{--</form>--}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>


        /**
         * Created by Arash on 9/16/2017.
         */

        $(document).on('click','#acceptRequest',function(){
            var certificate_id= $(this).attr('content');
            var certificate_record_id = $(this).attr('name');

            var token = $('#token').val();
            var td = $(this);
            var DOM = $('#table');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax
            ({
                url: "{{url('admin/acceptCertificate')}}",
                type:"post",

                data:{certificate_record_id:certificate_record_id,certificate_id:certificate_id,_token:token},
                success:function(response)
                {
                    $(td).parentsUntil(DOM,'tr').fadeOut(2000);
                    $(td).parentsUntil(DOM,'tr').empty();
                    swal
                    ({
                        title: '',
                        text: response,
                        type:'success',
                        confirmButtonText: 'بستن'
                    });
                },
                error:function (error) {
                    if(error.status === 500)
                    {
                        swal
                        ({
                            title: '',
                            text: 'خطایی رخ داده است.لطفا با بخش پشتیبانی تماس بگیرید',
                            type:'info',
                            confirmButtonText: 'بستن'
                        });
                        console.log(error);
                    }

                    if(error.status === 422)
                    {
                        console.log(error);
                        var errors = error.responseJSON; //this will get the errors response data.
                        //show them somewhere in the markup
                        //e.g
                        var  errorsHtml = '';

                        $.each(errors, function( key, value ) {
                            errorsHtml +=  value[0] + '\n'; //showing only the first error.
                        });
                        swal({
                            title: "",
                            text: errorsHtml,
                            type: "info",
                            confirmButtonText: "بستن"
                        });
                    }
                }
            })
        });

    </script>

    <script>
        function formatNumber (num) {
            return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
        }
    </script>
    <script>
        $('.rate').on('keyup', function() {
            var rate=$(this).parents('tr').find('.rate').val();
            var count=$(this).parents('tr').find('.count').val();

            var price = rate * count;
            $(this).parents('tr').find('.price').val(formatNumber(price));
        });

    </script>
@endsection
