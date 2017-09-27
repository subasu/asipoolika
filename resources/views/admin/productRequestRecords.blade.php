@extends('layouts.adminLayout');
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
                 <textarea id="why_not" style="text-align: right" maxlength="300" required="required" class="form-control" name="why_not" data-parsley-trigger="keyup" data-parsley-minlength="20" data-parsley-maxlength="300" data-parsley-minlength-message="شما حداقل باید 20 کاراکتر وارد کنید"
                           data-parsley-validation-threshold="10"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" id="why_not_btn" name="why_not_btn" class="btn btn-primary col-md-12">ثبت دلیل</button>
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
                        <th style="text-align: center ;">شماره</th>
                        <th style="text-align: center ;">عنوان درخواست</th>
                        <th style="text-align: center ;">مقدار</th>
                        <th style="text-align: center ;">نرخ (به تومان)</th>
                        <th style="text-align: center ;">قیمت</th>
                        <th style="text-align: center ;">عملیات</th>
                    </tr>
                    </thead>

                    <tbody>
                    {{--<form id="serviceDetailForm">--}}
                        {{ csrf_field() }}
                        <input type="hidden" id="token" name="csrf-token" value="{{ csrf_token() }}">
                        @foreach($requestRecords as $requestRecord)
                            <tr>
                                <td class="td">{{$requestRecord->id}}</td>
                                <td class="td">{{$requestRecord->title}}</td>
                                <td class="td" id="count" content="{{$requestRecord->count}}">{{$requestRecord->count}} {{$requestRecord->unit_count}}</td>
                                <input type="hidden" class="count" value="{{$requestRecord->count}}" name="count">
                                <input type="hidden" class="" value="2000" name="count">
                                <td class="td"><input type="text" class="form-control rate" id="rate"  name="rate"/></td>
                                <td class="td"><input type="text" class="form-control price" id="price" name="price"/></td>
                                <td class="td"><button class="btn btn-link btn-round" data-toggle="tooltip" title="{{$requestRecord->description}}"> توضیحات
                                </button>
                                <input id="acceptRequest" content="{{$requestRecord->id}}" name="{{$requestRecord->request_id}}" type="button" class="btn btn-success" required value="پیگیری" />
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
        //alert(id);
        var requestId = $(this).attr('name');
        //alert(requestId);
        var rate  = $('#rate').val();
        var price = $('#price').val();
        var token = $('#token').val();
        var td = $(this);
        var DOM = $('#table');

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
        }else
        {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });

            //var formData = new formData('#serviceDetailForm').serialize();
            $.ajax
            ({
                url: "{{Url('admin/acceptServiceRequest')}}",
                type:"post",
                context:td,
                data:{'rate':rate,'price':price,'id':id,'requestId':requestId,'_token':token},
                success:function(response)
                {
                    $(td).parentsUntil(DOM,'tr').fadeOut(2000);
                    $(td).parentsUntil(DOM,'tr').empty();
                    swal
                    ({
                        title: '',
                        text: response,
                        type:'info',
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
        }
    });

    $(document).on('click','#refuseRequest',function(){
        var id = $(this).attr('content');
        var requestId = $(this).attr('name');
        var td = $(this);
        var DOM = $('#table');
        $('#why_not_modal').modal('show');
        $('#why_not_btn').click(function(){
            var whyNot = $('#why_not').val();
            var token = $('#token').val();

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
                data:{'id':id,'requestId':requestId,'whyNot':whyNot,'_token':token},
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
                        text: response,
                        type:'info',
                        confirmButtonText: 'بستن'
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
        $(document).on('keyup','.rate',function () {
           //alert('hello');
            var rate  = $('input.rate').val();
            var count = $('input.count').val();
            var price = rate * count;
            $('#price').val(formatNumber(price));
            //alert(rate * count);
            //alert(count);
        });
    </script>
@endsection
