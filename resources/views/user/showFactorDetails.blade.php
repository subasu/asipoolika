
@extends('layouts.adminLayout')
@section('content')
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>
                        جزییات خلاصه تنظیمی
                    </h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link" data-toggle="tooltip" title="جمع کردن"><i
                                        class="fa fa-chevron-up"></i></a>
                        </li>
                        <li><a class="close-link" data-toggle="tooltip" title="بستن"><i class="fa fa-close"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="col-md-12">
                    <div class="x_content">
                        <table style="direction:rtl;text-align: center" id="example"
                               class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <input type="hidden" id="token" value="{{ csrf_token() }}">
                            <thead>
                            <tr>

                                <th style="text-align: center" class="col-md-1">شناسه</th>
                                <th style="text-align: center" class="">شماره فاکتور</th>
                                <th style="text-align: center;border-right: 1px solid #e0e0d1" class="">مبلغ کل</th>

                            </tr>
                            </thead>
                            <tbody id="change">

                            @foreach($bills as $bill)
                                <tr class="unit">
                                    <td>
                                        {{$bill->id}}
                                    </td>
                                    <td>
                                        فاکتور شماره: {{$bill->factor_number}}
                                    </td>
                                    <td style="border-right: 1px solid #e0e0d1">
                                        {{number_format($bill->final_price)}}
                                    </td>

                                </tr>
                            @endforeach

                        </table>
                        <input style="margin-left: 40%; width: 20%;" type="button" id="finish" value="تایید خلاصه تنظیمی" content="{{$bill->request_id}}" class="btn btn-primary">
                    </div>
                </div>


            </div>

        </div>


    <script>
        $(document).on('click','#finish',function(){
            var requestId = $(this).attr('content');
            var token     = $('#token').val();

            swal({
                    title: "آیا از ثبت درخواست مطمئن هستید؟",
                    text: "",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "	#5cb85c",
                    cancelButtonText: "خیر ، منصرف شدم",
                    confirmButtonText: "بله ثبت شود",
                    closeOnConfirm: true,
                    closeOnCancel: true
                },
                function (isConfirm) {
                    if (isConfirm)
                    {
                        $.ajax
                        ({
                            url  : "{{url('user/acceptPreparedSummarize')}}",
                            type : 'post',
                            data : {'requestId' : requestId , '_token' : token},
                            success : function (response) {
                                swal
                                ({
                                    title: '',
                                    text: response,
                                    type: 'info',
                                    confirmButtonText: "بستن"
                                });
                                setTimeout(function () {
                                    window.location.href='../dailyWorks/factors';
                                },2000);

                            },error : function (error) {
                                swal
                                ({
                                    title: '',
                                    text: 'خطایی رخ داده است ، لطفا با بخش پشتیبانی تماس بگیرید',
                                    type: 'info',
                                    confirmButtonText: "بستن"
                                });
                                console.log(error);

                            }
                        })
                    }
            });
        });

    </script>

    {{--edit user's status by user-id --}}
@endsection

