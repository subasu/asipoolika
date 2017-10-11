@extends('layouts.adminLayout');
@section('content')
    <!-- Modal -->
    <div id="messageModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" style="font-size: 20px;">&times;</button>
                    <h4 class="modal-title" dir="rtl">ثبت پیام</h4>
                </div>
                <div class="modal-body" style="text-align: right">
                    <label for="why_not" style="font-size: 20px;">لطفا پیام خود را در قسمت پایین تایپ نمایید</label>
                    <textarea id="message" style="text-align: right" maxlength="10000" required="required"
                              class="form-control" name="message" data-parsley-trigger="keyup"
                              data-parsley-minlength="20" data-parsley-maxlength="300"
                              data-parsley-minlength-message="شما حداقل باید 20 کاراکتر وارد کنید"
                              data-parsley-validation-threshold="10"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" id="sendMessage" name="sendMessage" class="btn btn-primary col-md-12">ثبت
                        پیام
                    </button>
                    <input type="hidden" id="token" value="{{ csrf_token() }}">
                </div>
            </div>

        </div>
    </div>
    <style>
        .ticketInfo {
            background-color: white !important;
        }
        .question {
            background-color: #5bc0de !important;
            padding: 10px 14px !important;
            margin-bottom: 0 !important;
            color: #fff;
        }
        .answer {
            background-color: #0275d8 !important;
            padding: 10px 14px !important;
            margin-bottom: 0 !important;
            color: #fff;
        }
        .font-size-30 {
            font-size: 30px;
            text-align: right;
        }
        .message-content{
            height: max-content;height:-moz-max-content;margin-bottom: 0 !important;
        }
    </style>
    @foreach($tickets as $ticket)
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h1 dir="rtl" class="col-md-1 pull-right font-size-30">وضعیت:</h1>
                        @if($ticket->active == 0)
                            <h1 class="col-md-1 pull-right font-size-30 text-success">فعال</h1>
                        @endif
                        @if($ticket->active == 1)
                            <h1 class="col-md-3 pull-right font-size-30 text-dander">بسته شده</h1>
                        @endif
                        {{--@if($ticket->active == 1)--}}
                            {{--<h1 class="col-md-3 pull-right font-size-30 text-danger">اتمام از طرف ادمین</h1>--}}
                        {{--@endif--}}
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
                        <div class="x_content " style="float: none;" dir="rtl">
                            <div class="item form-group">
                                <div class="col-md-5 col-sm-7 col-xs-12">
                                    <input id="title" value="{{$ticket->user->unit->title}}"
                                           class="form-control col-md-7 col-xs-10 ticketInfo" name="title" disabled
                                           type="text">
                                </div>
                                <label class="control-label col-md-1 col-sm-1 col-xs-2 text-center" for="unit_id">
                                    واحد ارسال کننده</label>
                            </div>
                            <div class="item form-group">
                                <div class="col-md-5 col-sm-8 col-xs-12">
                                    <input id="title" value="{{$ticket->title}}"
                                           class="form-control col-md-7 col-xs-10 ticketInfo"
                                           name="title" disabled type="text" style="margin-bottom: 1.5%;">
                                </div>
                                <label class="control-label col-md-1 col-sm-1 col-xs-2 " for="title">عنوان تیکت</label>
                            </div>
                            <div class="item form-group">
                                <div class="col-md-11 col-sm-8 col-xs-12">
                                    <div id="description" name="description" disabled
                                         style="padding: 2%;"
                                         class="message-content form-control col-md-7 col-xs-10 ticketInfo">{{$ticket->description}}</div>
                                </div>
                                <label class="control-label col-md-1 col-sm-1 col-xs-2" for="description">متن
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
                            <div class="x_content" style="">{{-- AdminConversation--}}
                                <div class="item" dir="rtl">
                                    <h5 class="control-label col-md-12 col-xs-12 question"><i
                                                class="fa fa-comments"></i> پیام کاربر
                                    </h5>
                                    <div id="description" name="description"
                                         class="form-control col-md-12 col-xs-12 text-white"
                                         style="height: max-content;height:-moz-max-content;margin-bottom: 0 !important;">
                                        <p>{{$message->content}}</p></div>
                                </div>
                                <div class="item" dir="rtl">
                                    <h5 class=" col-md-12 col-xs-12 bg-primary answer" for="description"><i
                                                class="fa fa-comments"></i> پاسخ مسئول
                                    </h5>
                                    @if(count($message->answer))
                                        <div id="description" name="description" style="height: max-content;height:-moz-max-content;margin-bottom: 0 !important;"
                                             class="form-control col-md-12 col-xs-12 answer-text">{{$message->answer}}</div>
                                    @endif
                                    @if(!count($message->answer))
                                        <div id="description" name="description"
                                             class="form-control col-md-12 col-xs-12 ">پاسخی داده نشده
                                        </div>
                                    @endif
                                </div>
                                @if($message->ticket->unit_id === \Illuminate\Support\Facades\Auth::user()->unit_id && !count($message->answer) && \Illuminate\Support\Facades\Auth::user()->is_supervisor == 1  && $ticket->active == 0)
                                    <div class="col-md-12">
                                        <br>
                                        <button id="adminMessage" content="{{$message->id}}" type="button"
                                                class="col-md-2 btn btn-primary">پاسخ گویی به پیام
                                        </button>
                                    </div>
                                @endif

                            </div>
                        @endforeach

                        <div class="x_content"
                             style="float: none !important; padding-top: 1%;">{{-- AdminConversation--}}
                            <div class="item form-group col-md-12" dir="rtl">
                                <hr>
                                <div class="col-md-12 col-xs-12">
                                    @if($ticket->sender_user_id === \Illuminate\Support\Facades\Auth::user()->id && $ticket->active == 0)

                                        <button id="userMessage" style="" content="{{$message->ticket_id}}"
                                        class="btn btn-info col-md-2 ">ارسال پیام جدید
                                        </button>
                                        <button id="endTicket" content="{{$message->ticket_id}}"
                                        class="btn btn-danger  col-md-2">بستن تیکت
                                        </button>
                                    @endif
                                    @if($ticket->unit_id === \Illuminate\Support\Facades\Auth::user()->unit_id && \Illuminate\Support\Facades\Auth::user()->is_supervisor == 1  && $ticket->active == 0)
    {{----}}
                                        {{--<button id="userMessage" style="" content="{{$message->ticket_id}}"--}}
                                                {{--class="btn btn-info col-md-2 ">ارسال پیام جدید--}}
                                        {{--</button>--}}
                                        <button id="endTicket" content="{{$message->ticket_id}}"
                                                class="btn btn-danger  col-md-2">بستن تیکت
                                        </button>
                                    @endif
                                {{--@if($ticket->user_id !== \Illuminate\Support\Facades\Auth::user()->id && $ticket->active == 0)--}}
                                    {{--<button id="adminEndTicket" content="{{$message->ticket_id}}"--}}
                                            {{--style="" class="btn btn-danger col-md-2">بستن تیکت--}}
                                    {{--</button>--}}
                                {{--@endif--}}
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

        </div>
        @endif
        <!-- admin message script -->
        <script>
            $(document).on('click', '#adminMessage', function () {
                messageId = $(this).attr('content');
                // alert(messageId);
                var token = $('#token').val();
                $('#messageModal').modal('show');
                $('#sendMessage').click(function () {
                    var message = $('#message').val();
                    if (message == '' || message == null) {
                        swal({
                            title: "",
                            text: "لطفا پیام  خود را تایپ نمایید سپس دکمه ثبت را بزنید.",
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
                        url: "{{Url('admin/adminSendMessage')}}",
                        type: "post",
                        data: {'messageId': messageId, 'message': message, '_token': token},
                        success: function (response) {
                            swal({
                                title: "",
                                text: response,
                                type: "info",
                                confirmButtonText: "بستن"
                            });
                            window.location.reload();
                        }, error: function (error) {
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
                var token = $('#token').val();
                $('#messageModal').modal('show');
                $('#sendMessage').click(function () {
                    var message = $('#message').val();
                    if (message == '' || message == null) {
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
                        url: "{{Url('user/userSendMessage')}}",
                        type: "post",
                        data: {'ticketId': ticketId, 'message': message, '_token': token},
                        success: function (response) {
                            swal({
                                title: "",
                                text: response,
                                type: "info",
                                confirmButtonText: "بستن"
                            });
                            window.location.reload();
                        }, error: function (error) {
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
            $(document).on('click', '#endTicket', function () {
                var ticketId = $(this).attr('content');
                var token = $('#token').val();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                })
                $.ajax
                ({
                    url: "{{Url('user/endTicket')}}",
                    type: "post",
                    data: {'ticketId': ticketId, '_token': token},
                    success: function (response) {
                        swal({
                            title: "",
                            text: response,
                            type: "info",
                            confirmButtonText: "بستن"
                        });
                        window.location.reload();
                    }, error: function (error) {
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

        {{--<script>--}}
            {{--$(document).on('click', '#adminEndTicket', function () {--}}
                {{--var ticketId = $(this).attr('content');--}}
                {{--var token = $('#token').val();--}}
                {{--$.ajaxSetup({--}}
                    {{--headers: {--}}
                        {{--'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')--}}
                    {{--}--}}
                {{--})--}}
                {{--$.ajax--}}
                {{--({--}}
                    {{--url: "{{Url('admin/adminEndTicket')}}",--}}
                    {{--type: "post",--}}
                    {{--data: {'ticketId': ticketId, '_token': token},--}}
                    {{--success: function (response) {--}}
                        {{--swal({--}}
                            {{--title: "",--}}
                            {{--text: response,--}}
                            {{--type: "info",--}}
                            {{--confirmButtonText: "بستن"--}}
                        {{--});--}}
                        {{--window.location.reload();--}}
                    {{--}, error: function (error) {--}}
                        {{--swal({--}}
                            {{--title: "",--}}
                            {{--text: "خطا  ، لطفا با بخش پشتیبانی تماس بگیرید",--}}
                            {{--type: "warning",--}}
                            {{--confirmButtonText: "بستن"--}}
                        {{--});--}}
                        {{--console.log(error);--}}
                    {{--}--}}
                {{--});--}}
            {{--});--}}
        {{--</script>--}}
@endsection