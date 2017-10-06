@extends('layouts.adminLayout')
@section('content')

        <!-- Modal -->
<div id="commentModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="direction: ltr; font-size: 20px;">رد درخواست</h4>
            </div>
            <div class="modal-body" style="direction: rtl;text-align:right;">
                <label for="comment" style="direction: rtl; font-size: 20px;">لطفا دلیل رد درخواست را بطور کامل تایپ کنید.</label>
                <textarea style="" class="form-control" id="comment" name="comment"></textarea>
            </div>
            <div class="modal-footer  col-md-12">
                <button style="margin-left:40%; width: 30%;font-size: 20px;" type="button" class="btn btn-primary" id="sub" data-dismiss="modal">ثبت</button>
            </div>
        </div>
        <input type="hidden" id="token" value="{{ csrf_token() }}">
    </div>
</div>


<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2 style="color:#e6005c;direction: rtl"><i class="fa fa-check"></i> ابلاغ درخواست به کارپرداز</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link" data-toggle="tooltip" title="جمع کردن"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li><a class="close-link" data-toggle="tooltip" title="بستن"><i class="fa fa-close"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
         <input type="hidden" id="request_id" value="{{$id}}">
            <div class="x_content">
                {{--<form id="serviceDetailForm">--}}
                <div class="row">
                    <div id="impart_div" class="col-md-7 col-xs-12 col-md-offset-4" style="direction: rtl;text-align: right">
                        <form class="form-horizontal form-label-left input_mask">
                            <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                                <label for="heard"> نام کاربر : </label>
                                <select id="heard" class="form-control" required>
                                    <option value="">کارپرداز را انتخاب کنید ...</option>
                                    @foreach($users as $user)
                                    <option value="{{$user->id}}">{{$user->title.chr(10).$user->name.chr(10).$user->family}}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{--<div class="ln_solid"></div>--}}
                            <div class="form-group">
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                    {{--<button type="submit" class="btn btn-primary col-md-6">Cancel</button>--}}
                                    <button type="button" id="impart_send" class="btn btn-primary col-md-8">ابلاغ شود</button>
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="form-group" id="return_button" style="display: none">
                        <div class="col-md-6 col-sm-9 col-xs-12 col-md-offset-4">
                            {{--<button type="submit" class="btn btn-primary col-md-6">Cancel</button>--}}
                            <a href="{{url('admin/confirmServiceRequestManagement')}}" class="btn btn-danger col-md-7">بازگشت به درخواست های تایید شده</a>
                        </div>
                    </div>
                </div>
                {{--</form>--}}
            </div>
        </div>
    </div>

    <script>
        $("#impart_send").click(function() {
            var user = $.trim($("#heard option:selected").val());
            var request_id=$('#request_id').val();
            $.ajax({
                url: "{{ url('admin/impart') }}",
                type: 'GET',
                dataType: 'json',
                data: {user:user,request_id:request_id},
                success: function(response) {
                    if(response.n==1)
                    {
                        new PNotify({
                            title: 'پیام سیستم',
                            text: response.message,
                            type: 'success'
                        });
                        $('#impart_div').remove();
                        $('#return_button').show();
                    }
                    else if (response.n==2)
                    {
                        new PNotify({
                            title: 'پیام سیستم : ',
                            text: response.message,
                            type: 'danger'
                        });
                    }
                    else if (response.n==3)
                    {
                        new PNotify({
                            title: 'پیام سیستم : ',
                            text: response.message,
                            type: 'warning'
                        });
                    }
                },
                error: function(error) {
                    var errors = error.responseJSON;
                    console.log(errors);
                }
            });
        });
    </script>
@endsection
