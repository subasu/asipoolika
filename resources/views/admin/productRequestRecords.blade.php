@extends('layouts.adminLayout')
@section('content')
<!-- Modal -->
<div id="why_not_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" style="font-size: 20px;">&times;</button>
                <h4 class="modal-title">رد رکورد درخواست</h4>
            </div>
            <div class="modal-body" style="text-align: right">
                <label for="why_not" style="font-size: 20px;">دلیل خود برای رد این درخواست را بنویسید تا به اطلاع درخواست کننده برسد</label>
                 <textarea id="why_not" style="text-align: right" maxlength="300" required="required" class="form-control why_not" name="why_not" data-parsley-trigger="keyup" data-parsley-minlength="20" data-parsley-maxlength="300" data-parsley-minlength-message="شما حداقل باید 20 کاراکتر وارد کنید"
                           data-parsley-validation-threshold="10"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" id="why_not_btn" content=""  name="" class="btn btn-primary col-md-12">ثبت دلیل</button>
            </div>
        </div>

    </div>
</div>

<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title" style="direction: rtl">
                @if(!empty($requestRecords[0]))
                    <input type="hidden" value="{{$requestRecords[0]->id}}" name="request_id">
                    <input type="hidden" value="{{$user->unit->title}}" name="user_unit_title" id="user_unit_title">
                    <h2><i class="fa fa-list"></i> لیست رکوردهای درخواست کالای شماره :  {{$requestRecords[0]->request_id}} | ثبت شده توسط :   {{$requestRecords[0]->request->user->name}} {{$requestRecords[0]->request->user->family}} از واحد {{$requestRecords[0]->request->user->unit->title}} | <span style="color: tomato;font-weight: bold">تعداد رکوردها : {{$requestRecords->count()}} رکورد</span></h2>
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
                        <th style="text-align: center ;">عنوان درخواست</th>
                        <th style="text-align: center ;">مقدار</th>
                        @if($user->unit->title=='تدارکات')
                        <th style="text-align: center ;">نرخ (به تومان)</th>
                        <th style="text-align: center ;">قیمت</th>
                        @endif
                        <th class="col-md-3" style="text-align: center ;">عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    {{--<form id="serviceDetailForm">--}}
                        {{ csrf_field() }}
                        <input type="hidden" id="token" name="csrf-token" value="{{ csrf_token() }}">
                        @foreach($requestRecords as $requestRecord)
                            <tr>
                                <input type="hidden" value="{{$requestRecord->id}}" class="record_id">
                                <th style="text-align: center">{{$requestRecord->id}}</th>
                                <td>{{$requestRecord->title}}</td>
                                <td id="count" content="{{$requestRecord->count}}">{{$requestRecord->count}} {{$requestRecord->unit_count}}</td>
                                <input type="hidden" class="count" value="{{$requestRecord->count}}" name="count">
                                {{--<input type="hidden" class="" value="2000" name="count[]">--}}
                                @if($user->unit->title=='تدارکات')
                                <td><input type="text" class="form-control rate" id="rate"  name="rate[]"/></td>
                                <td><input type="text" class="form-control price" id="price" content="content" name="price[]" style="font-size:16px;color:red"/></td>
                                @endif
                                <td><button class="btn btn-link btn-round" data-toggle="tooltip" title="{{$requestRecord->description}}"> توضیحات
                                </button>
                                <input id="acceptRequest" content="{{$requestRecord->id}}" name="{{$requestRecord->request_id}}" type="button" class="btn btn-success" required value="تایید" />
                                <input id="refuseRequest" content="{{$requestRecord->id}}" name="{{$requestRecord->request_id}}" type="button" class="btn btn-danger"  required value="رد کردن" /></td>
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
        var id= $(this).attr('content');
        var requestId = $(this).attr('name');
        if(user_unit_title=='تدارکات')
        {
            var rate=$(this).parents('tr').find('.rate').val();
            var price=$(this).parents('tr').find('.price').val();
            price = price.replace(',', '');
            if(rate == '' || rate == null)
            {
                $('#rate').focus();
                $('#rate').css('border-color','red');
                return false;
            }
            else if(price == '' || price == null)
            {
                $('#price').focus();
                $('#price').css('border-color','red');
                return false;
            }
        }

        var token = $('#token').val();
        var td = $(this);
        var DOM = $('#table');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            //var formData = new formData('#serviceDetailForm').serialize();

            $.ajax
            ({
                url: "{{url('admin/acceptProductRequest')}}",
                type:"post",
//                context:td,
                data:{rate:rate,price:price,id:id,requestId:requestId,_token:token},
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

    $(document).on('click','#refuseRequest',function(){
//        request record id
        var id = $(this).attr('content');
        var requestId = $(this).attr('name');
        //set the request record id into the modal for having access in why_not_btn
        $('#why_not_btn').attr('content',id);
        $('#why_not_btn').attr('name',requestId);
        var td = $(this);
        var DOM = $('#table');
        $('#why_not_modal').modal('show');
        $('#why_not_btn').click(function(){
            var request_record_id=$(this).attr('content');
            var request_id=$(this).attr('name');
//            var whyNot =$(this).find('textarea.why_not').val();
            var whyNot=$(this).parents('div').find('.why_not').val();
            var token = $('#token').val();
//            console.log(request_record_id+'/'+request_id+'/'+whyNot);
//            return false;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax
            ({
                url:"{{Url('admin/refuseRequestRecord')}}",
                type:'post',
                context:td,
                data:{request_record_id:request_record_id,requestId:request_id,whyNot:whyNot,_token:token},
                beforeSend:function()
                {
                    if(whyNot == '' || whyNot == null)
                    {
                        swal
                        ({
                            title: '',
                            text: 'لضفا توضیحاتی برای رد درخواست بنویسید سپس دکمه ثبت را بزنید',
                            type:'warning',
                            confirmButtonText: 'بستن'
                        });
                        return false;
                    }
                },
                success:function (response) {
                    $(td).parentsUntil(DOM,'tr').fadeOut(2000);
                    $(td).parentsUntil(DOM,'tr').empty();
                    swal
                    ({
                        title: '',
                        text: response.msg,
                        type: response.class,
                        confirmButtonText: 'بستن'
                    },function() {
                        // Redirect the user
                        window.location.reload();
                        $('#why_not_modal').modal('hide');
                        $('#why_not').val('');
                    });




                },error:function(error)
                {
                    if(error.status === 500)
                    {
                        swal
                        ({
                            title: '',
                            text: 'خطایی رخ داده است.لطفا با بخش پشتیبانی تماس بگیرید',
                            type:'info',
                            confirmButtonText: 'بستن'
                        });
                        console.log();
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
            });
        });
    });

</script>
 {{--<script>--}}
        {{--$(document).ready(function(){--}}
            {{--$('.rate').keypress(function(e){--}}
                {{--if (this.value.length == 0 && e.which == 48 ){--}}
                    {{--return false;--}}
                {{--}--}}
            {{--});--}}
            {{--$('.rate').keyup(function(){--}}
                {{--var rate=$(this).val();--}}
                {{--var record_count=$('#count').attr('content');--}}
                {{--var a=$(this).closest('tr').children('input.show_price').val();--}}
                {{--alert(a);--}}
                {{--if(rate.length>=4)--}}
                {{--{--}}
                    {{--var price=rate*record_count;--}}
                    {{--$(this).closest('tr').children('input.price').val(price);--}}
                {{--}--}}
                {{--else--}}
                    {{--$('#sellPriceS').html('عدد کامل وارد کنید');--}}
            {{--});--}}

            {{--function validateMaxLength()--}}
            {{--{--}}
                {{--var text = $(this).val();--}}
                {{--var maxlength = $(this).data('maxlength');--}}
                {{--if(maxlength > 0)--}}
                {{--{--}}
                    {{--$(this).val(text.substr(0, maxlength));--}}
                {{--}--}}
            {{--}--}}
        {{--});--}}
    {{--</script>--}}
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



$('.icode').on('change', function() {
    $(this).parents('tr').find('.description').val($(this).val());
});

    </script>
@endsection
