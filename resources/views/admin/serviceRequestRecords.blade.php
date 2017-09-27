@extends('layouts.adminLayout')
@section('content')

    <!-- Modal -->
    <form>
        {{csrf_field()}}
        <div id="commentModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title" style="direction: rtl; font-size: 20px;">رد درخواست</h4>
                    </div>
                    <div class="modal-body">
                        <h4 style="direction: rtl; font-size: 20px;">لطفا دلیل رد درخواست را بطور کامل تایپ کنید.</h4>
                        <textarea class="form-control" id="whyNot" name="whyNot" placeholder=""></textarea>
                    </div>
                    <div class="modal-footer">
                        <button style="margin-left:40%; width: 30%;font-size: 20px;" type="button" class="btn btn-primary" id="sub" data-dismiss="modal">ثبت</button>
                    </div>
                </div>
                <input type="hidden" id="token" value="{{ csrf_token() }}">
            </div>
        </div>
    </form>

        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title" style="direction:rtl;">
                        @if(!empty($records[0]))
                            <input type="hidden" value="{{$records[0]->id}}" name="request_id">
                            <h2><i class="fa fa-list"></i> لیست رکوردهای درخواست خدمت شماره : {{$records[0]->request_id}} | ثبت شده توسط :   {{$records[0]->request->user->name}} {{$records[0]->request->user->family}} از واحد {{$records[0]->request->user->unit->title}} | <span style="color: tomato;font-weight: bold">تعداد رکوردها : {{$records->count()}} رکورد</span></h2>
                        @endif
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link" data-toggle="tooltip" title="جمع کردن"><i class="fa fa-chevron-up"></i></a>
                            </li>
                            <li><a class="close-link" data-toggle="tooltip" title="بستن"><i class="fa fa-close"></i></a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <table style="direction:rtl;text-align: center;font-size:15px;" id="table" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th style="text-align: center ;">شناسه</th>
                                <th style="text-align: center ;">عنوان درخواست</th>
                                <th style="text-align: center ;">مقدار/تعداد</th>
                                <th style="text-align: center ;">نرخ (به تومان)</th>
                                <th style="text-align: center ;">قیمت</th>
                                <th style="text-align: center ;">عملیات</th>
                            </tr>
                            </thead>

                            <tbody>
                            {{--<form id="serviceDetailForm">--}}
                                {{ csrf_field() }}
                                <input type="hidden" id="token" name="csrf-token" value="{{ csrf_token() }}">
                            @foreach($records as $record)
                                <tr>
                                    <th style="text-align: center">{{$record->id}}</th>
                                    <td>{{$record->title}}</td>
                                    <td id="count" content="{{$record->count}}">{{$record->count}}</td>
                                    <input type="hidden" class="count" value="{{$record->count}}" name="count">
                                    <td><input type="text" class="form-control rate" id="rate"  name="rate[]"/></td>
                                    <td><input type="text" class="form-control price" id="price" content="content" name="price[]" style="font-size:16px;color:red"/></td>
                                    <td>
                                        <button class="btn btn-link btn-round" data-toggle="tooltip" title="{{$record->description}}"> توضیحات
                                        </button>
                                        <input id="acceptRequest" content="{{$record->id}}" name="{{$record->request_id}}" type="button" class="btn btn-success" required value="پیگیری" />
                                        <input id="refuseRequest" content="{{$record->id}}" name="{{$record->request_id}}" type="button" class="btn btn-danger"  required value="رد کردن" />
                                    </td>
                                </tr>
                            @endforeach()
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

            var rate=$(this).parents('tr').find('.rate').val();
            var price=$(this).parents('tr').find('.price').val();

            price = price.replace(',', '');
//        console.log(price);
//        $('#rate').attr('content',price);
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
                    url: "{{url('admin/acceptServiceRequest')}}",
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
            }
        });


        $(document).on('click','#refuseRequest',function(){
            var id = $(this).attr('content');
            var requestId = $(this).attr('name');
            var td = $(this);
            var DOM = $('#table');
            $('#commentModal').modal('show');

            $('#sub').click(function(){
                    var whyNot = $('#whyNot').val();
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



@endsection
