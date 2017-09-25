@extends('layouts.adminLayout');
@section('content')
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-8 col-sm-8 col-xs-12 col-md-offset-2">
            <div class="x_panel">
                <div class="x_title">
                    <h2>  ارسال تیکت
                    </h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                {{-- table --}}

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_content">
                        <form class="form-horizontal form-label-left" id="unit-send-form" method="POST">
                            {{ csrf_field() }}
                            <div class="item form-group">
                                <div class="col-md-9 col-sm-6 col-xs-12">
                                    <select class="form-control col-md-7 col-xs-12" style="direction: rtl;" name="units" id="units">
                                        <option value="واحد مربوطه را انتخاب نمایید" class="text-right">واحد مربوطه را انتخاب نمایید</option>
                                        <option value="تدارکات" class="text-right">واحد تدارکات</option>
                                        <option value="انفورماتیک" class="text-right">واحد انفورماتیک</option>
                                    </select>
                                </div>
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="unit_id"> واحد
                                    <span class="required" title="پر کردن این فیلد الزامی است">*</span></label>
                            </div>
                            <div class="item form-group" {{ $errors->has('title') ? ' has-error' : '' }}>
                                <div class="col-md-9 col-sm-6 col-xs-12">
                                    <input style="direction: rtl" id="title" class="form-control col-md-7 col-xs-12" name="title"
                                           placeholder=""
                                           required="required" type="text">
                                </div>
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">عنوان تیکت <span
                                            class="required" title="پر کردن این فیلد الزامی است"
                                            style="color:red">*</span>
                                </label>
                                @if ($errors->has('title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="item form-group">
                                <div class="col-md-9 col-sm-6 col-xs-12">
                                    <textarea style="direction: rtl" id="description" required="required" name="description"
                                              class="form-control col-md-7 col-xs-12"></textarea>
                                </div>
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">متن تیکت
                                    <span class="required" title="پر کردن این فیلد الزامی است"
                                            style="color:red">*</span>
                                </label>
                            </div>
                            <div class="ln_solid"></div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <button id="ticketSend" type="button" class="col-md-9 btn btn-primary">ثبت</button>
                                    <input type="hidden" id="token" value="{{ csrf_token() }}">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{--<script>--}}
            {{--$(document).ready(function(){--}}
                {{--//alert('hello');--}}
                {{--$.ajax--}}
                {{--({--}}
                    {{--url  : "{{Url('user/getUnits')}}",--}}
                    {{--type : "get",--}}
                    {{--dataType : "JSON",--}}
                    {{--success:function(response)--}}
                    {{--{--}}
                        {{--console.log(response);--}}
                        {{--var html;--}}
                        {{--$.each(response , function(index,value){--}}
                           {{--html += '<option value="'+value.id+'">'+value.title+'</option>';--}}
                        {{--});--}}
                        {{--$('#units').html(html);--}}
                    {{--},--}}
                    {{--error:function(error)--}}
                    {{--{--}}
                        {{--alert('error');--}}
                        {{--console.log(error);--}}

                    {{--}--}}
                {{--})--}}
            {{--});--}}
        {{--</script>--}}
        <script>
            $(document).on('click','#ticketSend',function(){
               //alert('hello');
                var unit ='';
                $("[name = 'units']").each(function () {
                   unit +=$(this).val();
                });
                //alert(unit);

                if(unit === 'واحد مربوطه را انتخاب نمایید')
                {
                    swal({
                        title: "",
                        text: 'لطفا واحد مربوطه را انتخاب نمایید سپس درخواست ثبت تیکت را بزنید',
                        type: "warning",
                        confirmButtonText: "بستن"
                    });
                    return false;
                }
                var title = $('#title').val();
                var description = $('#description').val();
                var token = $('#token').val();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });

                $.ajax
                ({
                    url  :"{{Url('user/sendTicket')}}",
                    type :"post",
                    data :{'unit':unit,'title':title,'description':description,'_token':token},
                    success:function(response)
                    {
//                        swal({
//                            title: "",
//                            text: response,
//                            type: "info",
//                            confirmButtonText: "بستن"
//                        });
                        window.location.href = 'ticketsManagement';

                    },error:function(error)
                    {
                        console.log(error);
                        if(error.status === 500)
                        {
                            swal({
                                title: "",
                                text: 'خطایی رخ داده است.لطفا با بخش پشتیبانی تماس بگیرید',
                                type: "warning",
                                confirmButtonText: "بستن"
                            });
                        }
                        else if(error.status === 422)
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
                                type: "warning",
                                confirmButtonText: "بستن"
                            });
                        }


                    }
                })

            });
        </script>
@endsection