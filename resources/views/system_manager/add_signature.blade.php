@extends('layouts.adminLayout')
@section('content')
    <!-- page content -->
    <div class="" role="main">
        <div class="">
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-8 col-sm-12 col-xs-12 col-md-offset-2">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2></h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link" data-toggle="tooltip" title="جمع کردن"><i
                                                class="fa fa-chevron-up"></i></a>
                                </li>
                                <li><a class="close-link" data-toggle="tooltip" title="بستن"><i class="fa fa-close"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <br/>
                            <form class="form-horizontal form-label-right input_mask" enctype="multipart/form-data" id="dealForm" style="direction: rtl;">
                                {{ csrf_field() }}
                                <input type="hidden" name="dealTypeName" id="dealTypeName" value="">
                                <input type="hidden" name="dealTypeEn" id="dealTypeEn" value="">
                                <input type="hidden" name="property_id" id="property_id" value="">
                                {{--<div class="row">--}}
                                    {{--<div class="col-md-12" style="">--}}
                                        {{--<p style="color:red;font-size: 18px;">چنانچه صاحب امضاء را بعنوان کاربر تعریف نکرده اید ابتدا  : <a href="" ><i class="fa fa-user"></i> کاربر جدید را ثبت کنید</a></p>--}}
                                    {{--</div>--}}
                                {{--</div>--}}

                                <label style="font-size: 20px;margin-bottom: 10px;"
                                       class="control-label pull-right col-md-12 col-sm-12 col-xs-12 form-group"> مشخصات
                                    مالک امضاء
                                </label>
                                {{--<div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback pull-right">--}}
                                <div class="col-md-6 col-sm-6 col-xs-12 form-group pull-right">
                                    <select class="form-control" id="unit_signature" name="unit_id">
                                        <option readonly>انتخاب واحد</option>
                                        @foreach($units as $unit)
                                            <option name="unit_id" value="{{$unit->id}}">{{$unit->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                {{--</div>--}}
                                <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                                    <select class="form-control" id="user_signature" name="users">
                                    </select>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback pull-right">
                                    <input type="checkbox"   style="text-align:right;" id="signature_priority" name="signature_priority" placeholder="درجه اهمیت" >

                                    <lable style="font-size: 120%;">در صورت اختیاری بودن امضاء کاربر تیک را بزنید.</lable>
                                    {{--<span class="fa fa-info-circle form-control-feedback right" aria-hidden="true"></span>--}}
                                </div>
                                <div class="row">
                                    <div class="input-group image-preview col-md-12">
                                        <input type="text" class="form-control image-preview-filename" disabled="disabled">
                                        <!-- don't give a name === doesn't send on POST/GET -->
                                        <span class="input-group-btn">
                                    <!-- image-preview-clear button -->
                                    <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
                                        <span class="glyphicon glyphicon-remove"></span> پاک کردن
                                    </button>
                                            <!-- image-preview-input -->
                                    <div class="btn btn-default image-preview-input">
                                        <span class="glyphicon glyphicon-folder-open"></span>
                                        <span class="image-preview-input-title">انتخاب تصویر امضاء</span>
                                        <input type="file" id="file" name="file" accept="image/png, image/jpeg, image/gif" name="input-file-preview"/>
                                        <!-- rename it -->
                                    </div>
                                    </span>
                                    </div><!-- /input-group image-preview [TO HERE]-->
                                </div>
                                <div class="ln_solid"></div>
                                <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-3 col-sm-offset-3 col-xs-offset-1">
                                    {{--<button type="submit" name="edit" id="edit" class="btn btn-primary col-md-3 col-sm-3 col-xs-5">ویرایش</button>--}}
                                    <button type="button" name="add_signature" id="add_signature"
                                            class="btn btn-success col-md-6 col-sm-3 col-xs-5">ثبت امضاء
                                    </button>
                                    <input type="hidden" id="userId" name="userId" value="0" />
                                    <input type="hidden" id="unitId" name="unitId" value="0" />
                                    <input type="hidden" id="forced" name="forced" value="1" />
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $("#unit_signature").change(function () {
                var $this = $(this);
                var id = $this.val();
                $.ajax({
                    url: "{{ url('unit_signature') }}",
                    type: 'GET',
                    dataType: 'json',
                    data: {unit_id: id},
                    success: function (response) {
                        var html;
                        html += '<option value="">صاحب امضاء را انتخاب کنید</option>';
                        $.each(response.users, function (index, value) {
                            html += '<option name="users" value="' + value['id'] + '">' + value['name'] + ' ' + value['family'] + '</option>';
                        });
                        $("#user_signature").html(html);
                    },
                    error: function (error) {
                        var errors = error.responseJSON;
                        console.log(errors);
                    }
                });
            });
        </script>
    <script>
        $('#signature_priority').change(function() {
            $('#forced').val(0);
        });
    </script>
    <script>
        $(document).on('click','#add_signature',function () {

                var unitId = "";
                $("[name='unit_id']:selected").each(function(){
                    unitId +=$(this).val();
                    $('#unitId').val(unitId);
                });
                //alert(unitId);

                var userId = "";
                $("[name='users']:selected").each(function () {
                   userId += $(this).val();
                   $('#userId').val(userId);
                });
                var file  = $('#file').val();
                var token = $('#token').val();
                if(unitId == '' || unitId == null)
                {
                    swal({
                        title: "",
                        text: 'لطفا واحد مربوطه را انتخاب نمایید',
                        type: "warning",
                        confirmButtonText: "بستن"
                    });
                    return false;
                }
                else if(userId == '' || userId == null)
                {
                    swal({
                        title: "",
                        text: 'لطفا کاربر مربوطه را انتخاب کنید',
                        type: "warning",
                        confirmButtonText: "بستن"
                    });
                    return false;
                }
                else if(file == '' || file == null)
                {
                    swal({
                        title: "",
                        text: 'لطفا فایل مربوط به امضاء را انتخاب کنید',
                        type: "warning",
                        confirmButtonText: "بستن"
                    });
                    return false;
                }else
                    {

                        var formData = new FormData($('#dealForm')[0]);
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                            }
                        });
                        $.ajax
                        ({
                            cache:false,
                            url  : "{{Url('systemManager/addSignature')}}",
                            type : 'POST',
                            processData :false,
                            contentType: false,
                            data : formData,
                            success:function(response)
                            {
                                swal({
                                    title: "",
                                    text: response,
                                    type: "info",
                                    confirmButtonText: "بستن"
                                });
                            },error:function(error)
                            {
                                swal({
                                    title: "",
                                    text: 'خطا در ثبت اطلاعات ، لطفا با بخش پشتیبانی تماس بگیرید',
                                    type: "warning",
                                    confirmButtonText: "بستن"
                                });
                                console.log(error);
                            }
                        });


                    }

        });
    </script>
@endsection