@extends('layouts.adminLayout')
@section('content')
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2> مدیریت واحدها</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link" data-toggle="tooltip" title="جمع کردن"><i
                                        class="fa fa-chevron-up"></i></a>
                        </li>
                        <li><a class="close-link" data-toggle="tooltip" title="بستن"><i class="fa fa-close"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <a href="{{url('admin/unitsCreate')}}" id="user-send" type="button" class="col-md-2 btn btn-primary" style="font-weight: bold;"><i
                            class="fa fa-plus"></i>
                    افزودن واحد جدید
                </a>
                <div class="x_content">
                    <table style="direction:rtl;text-align: center" id="example"
                           class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                        <input type="hidden" id="token" value="{{ csrf_token() }}">
                        <thead>
                        <tr>
                            <th style="text-align: center">ردیف</th>
                            <th style="text-align: center">عنوان</th>
                            <th style="text-align: center">تلفن</th>
                            <th style="text-align: center">توضیحات</th>
                            <th style="text-align: center">غیرفعال/ فعال</th>
                            <th style="text-align: center;border-right: 1px solid #d6d6c2">ویرایش</th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php $i = 0 ?>
                        @foreach($data as $val)
                            <tr class="unit">
                                <td>{{++$i}}</td>
                                <td>{{$val->title}}</td>
                                <td>{{$val->phone}} </td>
                                <td>{{$val->description}}</td>
                                <td>
                                    @if($val->main==0)
                                    @if($val->active ===1)
                                        <button value="{{$val->active}}" id="{{$val->id}}" class="btn btn-success">فعال</button>
                                    @else
                                        <button value="{{$val->active}}" id="{{$val->id}}" class="btn btn-danger">غیرفعال</button>
                                    @endif
                                        @else <span class="label label-warning" style="font-weight: lighter;font-size:13px;">غیر قابل تغییر</span>
                                    @endif
                                </td>
                                <td style="border-right: 1px solid #d6d6c2" id="{{$val->id}}">
                                    @if($val->main==0)
                                    <a class="btn btn-info"
                                       href="{{url('admin/unitsUpdate'.'/'.$val->id)}}">ویرایش</a>
                                    @else <span class="label label-warning" style="font-weight: lighter;font-size:13px;">غیرقابل ویرایش</span>
                                        @endif
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    {{--edit unit's status by unit-id --}}
        <script>
            $(document).on('click','.btn-success',function () {
                var unitId = $(this).attr('id');
                var status = $(this).val();
                var token  = $('#token').val();
                var button = $(this);
                swal({
                        title: "",
                        text: "آیا از غیرفعال کردن واحد اطمینان دارید؟",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "	#5cb85c",
                        cancelButtonText: "خیر ، منصرف شدم",
                        confirmButtonText: "بله غیرفعال شود",
                        closeOnConfirm: true,
                        closeOnCancel: true
                    },
                    function () {
                        $.ajax
                        ({
                            url     : "{{Url('admin/changeUnitStatus')}}/{{1}}",
                            type    : 'post',
                            data    : {'unitId':unitId,'_token':token},
                            context :  button,
                            //dataType:'json',
                            success : function (response)
                            {
                                $(button).text('غیر فعال');
                                $(button).toggleClass('btn-success btn-danger');
                                swal({
                                    title: "",
                                    text: response,
                                    type: "info",
                                    confirmButtonText: "بستن"
                                });
                            },
                            error : function(error)
                            {
                                console.log(error);
                                swal({
                                    title: "",
                                    text: "خطایی رخ داده است ، تماس با بخش پشتیبانی",
                                    type: "warning",
                                    confirmButtonText: "بستن"
                                });
                            }
                        });
                    });

            })
        </script>
        <script>
            $(document).on('click','.btn-danger',function () {
                var unitId = $(this).attr('id');
                var status = $(this).val();
                var token = $('#token').val();
                var button = $(this);
                swal({
                        title: "",
                        text: "آیا از غیرفعال کردن واحد اطمینان دارید؟",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "	#5cb85c",
                        cancelButtonText: "خیر ، منصرف شدم",
                        confirmButtonText: "بله غیرفعال شود",
                        closeOnConfirm: true,
                        closeOnCancel: true
                    },
                    function () {
                        $.ajax
                        ({
                            url: "{{Url('admin/changeUnitStatus')}}/{{2}}",
                            type: 'post',
                            data: {'unitId': unitId, '_token': token},
                            context: button,
                            //dataType:'json',
                            success: function (response) {
                                $(button).text('فعال');
                                $(button).toggleClass('btn-success btn-danger');
                                swal({
                                    title: "",
                                    text: response,
                                    type: "info",
                                    confirmButtonText: "بستن"
                                });
                            },
                            error: function (error) {
                                console.log(error);
                                swal({
                                    title: "",
                                    text: "خطایی رخ داده است ، تماس با بخش پشتیبانی",
                                    type: "warning",
                                    confirmButtonText: "بستن"
                                });
                            }
                        });
                    });


            });
        </script>
@endsection