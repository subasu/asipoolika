@extends('layouts.adminLayout')
@section('content')
    <!-- Modal -->
    <div id="messageModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button  type="button" class="close" data-dismiss="modal" style="font-size: 20px;">&times;</button>
                    <h4 class="modal-title" dir="rtl">ثبت پیام</h4>
                </div>
                <div class="modal-body" style="text-align: right">
                    <label for="why_not" style="font-size: 20px;">لطفا پیام خود را در قسمت پایین تایپ نمایید</label>
                    <textarea id="message" style="text-align: right" maxlength="10000" required="required" class="form-control" name="message" data-parsley-trigger="keyup" data-parsley-minlength="20" data-parsley-maxlength="300" data-parsley-minlength-message="شما حداقل باید 20 کاراکتر وارد کنید"
                              data-parsley-validation-threshold="10"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" id="sendMessage" name="sendMessage" class="btn btn-primary col-md-12">ثبت پیام</button>
                    <input type="hidden" id="token" value="{{ csrf_token() }}">
                </div>
            </div>

        </div>
    </div>

    @foreach($tickets as $ticket)
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h1 dir="rtl" style="">وضعیت:</h1>
                    @if($ticket->active == 0)
                        <h1 style="margin-left: 70%; margin-top: -49px;">فعال</h1>
                    @endif
                    @if($ticket->active == 2)
                        <h1 style="margin-left: 70%; margin-top: -49px;">اتمام از طرف کاربر</h1>
                    @endif
                    @if($ticket->active == 1)
                        <h1 style="margin-left: 70%; margin-top: -49px;">اتمام از طرف ادمین</h1>
                    @endif
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                {{-- table --}}

                    <div class="col-md-12 col-sm-12 col-xs-12 ">
                        <div class="x_content" dir="rtl">
                            <div class="item form-group">
                                <div class="col-md-9 col-sm-7 col-xs-12">
                                    <input id="title" value="{{$ticket->unit->title}}"
                                           class="form-control col-md-7 col-xs-10" name="title" disabled type="text"
                                           style="margin-bottom: 1.5%;">
                                </div>
                                <label class="control-label col-md-3 col-sm-1 col-xs-2" for="unit_id"> واحد</label>
                            </div>
                            <div class="item form-group">
                                <div class="col-md-9 col-sm-8 col-xs-12">
                                    <input id="title" value="{{$ticket->title}}" class="form-control col-md-7 col-xs-10"
                                           name="title" disabled type="text" style="margin-bottom: 1.5%;">
                                </div>
                                <label class="control-label col-md-3 col-sm-1 col-xs-2 " for="title">عنوان تیکت</label>
                            </div>
                            <div class="item form-group">
                                <div class="col-md-9 col-sm-8 col-xs-12">
                                    <textarea id="description" name="description" disabled
                                              class="form-control col-md-7 col-xs-10">{{$ticket->description}}</textarea>
                                </div>
                                <label class="control-label col-md-3 col-sm-1 col-xs-2" for="description">متن
                                    تیکت</label>
                            </div>
                        </div>
                    </div>

            </div>
        </div>
        @endforeach
        @if(count($messages))

            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2> گفتگــو ها
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
                    @foreach($messages as $message)
                        <div class="x_content" style="float: none !important;">{{-- AdminConversation--}}
                            @if($message->ticket->user_id !== \Illuminate\Support\Facades\Auth::user()->id)
                                <div class="col-md-2">
                                    <br>
                                    <br>
                                    <button id="adminMessage" content="{{$message->id}}" type="button"
                                            class="col-md-9 btn btn-primary">پاسخ گویی به پیام
                                    </button>
                                </div>
                            @endif
                            <div class="item form-group" dir="rtl">
                                <div class="col-md-4 col-xs-12">
                                    @if(count($message->answer))
                                        <textarea id="description" required="required" name="description" class="form-control col-md-1 col-xs-12" disabled>{{$message->answer}}</textarea>
                                    @endif
                                    @if(!count($message->answer))
                                        <textarea id="description" required="required" name="description" class="form-control col-md-1 col-xs-12" disabled>پاسخی داده نشده</textarea>
                                    @endif
                                </div>
                                <label class="control-label col-md-1 col-xs-12" for="description">پاسخ مسئول
                                </label>
                            </div>

                            <div class="item form-group " dir="rtl">
                                <div class="col-md-4 col-xs-12">
                                    <textarea id="description" required="required" name="description"
                                              class="form-control col-md-4 col-xs-12"
                                              disabled>{{$message->content}}</textarea>
                                </div>
                                <label class="control-label col-md-1 col-xs-12" for="description">پیام کاربر
                                </label>
                            </div>
                        </div>
                    @endforeach

                        <div class="x_content" style="float: none !important; padding-top: 1%;">{{-- AdminConversation--}}
                            <div class="item form-group col-md-12" dir="rtl">
                                <div class="col-md-2 col-xs-12">
                                    @if($ticket->user_id === \Illuminate\Support\Facades\Auth::user()->id && $ticket->active == 0)
                                        <button id="userMessage" style="margin-right: -10%;" content="{{$message->ticket_id}}" class="btn btn-info">ارسال پیام جدید</button>
                                        <button id="userEndTicket" content="{{$message->ticket_id}}" style="margin-right: 57%; margin-top: -25%;" class="btn btn-info">خاتمه تیکت</button>
                                    @endif
                                    @if($ticket->user_id !== \Illuminate\Support\Facades\Auth::user()->id && $ticket->active == 0)
                                            <button id="adminEndTicket" content="{{$message->ticket_id}}" style="margin-right: 57%;" class="btn btn-info">خاتمه تیکت</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                </div>
            </div>

        @endif
        @if(!count($messages))
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2> هنوز گفتگویی در رابطه با این تیکت صورت نگرفته است
                        </h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </li>
                            <li><a class="close-link"><i class="fa fa-close"></i></a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>


                </div>
            </div>

    @endif
    <!-- admin message script -->
    <script>
        $(document).on('click', '#adminMessage', function () {
            messageId = $(this).attr('content');
           // alert(messageId);

            var token=$('#token').val();
            $('#messageModal').modal('show');

            $('#sendMessage').click(function(){
                var message = $('#message').val();
                if(message == '' || message == null)
                {
                    swal({
                        title: "",
                        text: "لطفا پیام خود را تایپ نمایید سپس دکمه ثبت را بزنید.",
                        type: "warning",
                        confirmButtonText: "بستن"
                    });
                    return false;
                }
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                })

                $.ajax
                    ({
                        url  : "{{Url('admin/adminSendMessage')}}",
                        type : "post",
                        data :{'messageId':messageId , 'message' : message , '_token' : token},
                        success: function(response)
                        {
                            swal({
                                title: "",
                                text: response,
                                type: "info",
                                confirmButtonText: "بستن"
                            });
                          window.location.reload();
                        },error:function(error)
                        {
                            swal({
                                title: "",
                                text: "خطا در ثبت اطلاعات ، لطفا با بخش پشتیبانی تماس بگیرید",
                                type: "warning",
                                confirmButtonText: "بستن"
                            });
                            console.log(error);
                        }
                    })
            });

        });
    </script>

    <!-- user message script -->
    <script>
        $(document).on('click', '#userMessage', function () {
            var ticketId = $(this).attr('content');
            var token=$('#token').val();
            $('#messageModal').modal('show');

            $('#sendMessage').click(function(){
                var message = $('#message').val();
                if(message == '' || message == null)
                {
                    swal({
                        title: "",
                        text: "لطفا پیام خود را تایپ نمایید سپس دکمه ثبت را بزنید.",
                        type: "warning",
                        confirmButtonText: "بستن"
                    });
                    return false;
                }
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                })

                $.ajax
                ({
                    url  : "{{Url('user/userSendMessage')}}",
                    type : "post",
                    data :{'ticketId':ticketId , 'message' : message , '_token' : token},
                    success: function(response)
                    {
                        swal({
                            title: "",
                            text: response,
                            type: "info",
                            confirmButtonText: "بستن"
                        });
                        window.location.reload();
                    },error:function(error)
                    {
                        swal({
                            title: "",
                            text: "خطا در ثبت اطلاعات ، لطفا با بخش پشتیبانی تماس بگیرید",
                            type: "warning",
                            confirmButtonText: "بستن"
                        });
                        console.log(error);
                    }
                })
            });
        });
    </script>
    <script>
        $(document).on('click','#userEndTicket',function(){
           var ticketId = $(this).attr('content');
           var token    = $('#token').val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            })
            $.ajax
            ({
               url  : "{{Url('user/userEndTicket')}}",
               type : "post",
               data : {'ticketId':ticketId , '_token':token},
                success:function(response)
                {
                    swal({
                        title: "",
                        text:  response,
                        type: "info",
                        confirmButtonText: "بستن"
                    });
                    window.location.reload();
                },error:function(error)
                {
                    swal({
                        title: "",
                        text: "خطا  ، لطفا با بخش پشتیبانی تماس بگیرید",
                        type: "warning",
                        confirmButtonText: "بستن"
                    });
                    console.log(error);
                }
            });
        });
    </script>

    <script>
        $(document).on('click','#adminEndTicket',function(){
            var ticketId = $(this).attr('content');
            var token    = $('#token').val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            })
            $.ajax
            ({
                url  : "{{Url('admin/adminEndTicket')}}",
                type : "post",
                data : {'ticketId':ticketId , '_token':token},
                success:function(response)
                {
                    swal({
                        title: "",
                        text:  response,
                        type: "info",
                        confirmButtonText: "بستن"
                    });
                    window.location.reload();
                },error:function(error)
                {
                    swal({
                        title: "",
                        text: "خطا  ، لطفا با بخش پشتیبانی تماس بگیرید",
                        type: "warning",
                        confirmButtonText: "بستن"
                    });
                    console.log(error);
                }
            });

        });
    </script>
@endsection