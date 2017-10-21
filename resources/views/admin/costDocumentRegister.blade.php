@extends('layouts.adminLayout')
@section('content')
    <input type="hidden" value="{{$user_id=\Illuminate\Support\Facades\Auth::user()->id}}">
    <div class="page-title">
        <div class="title_right">
            <h3>
                ثبت سند هزینه
            </h3>
        </div>
    </div>
    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>
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

                <div class="col-md-12 col-sm-8 col-xs-12">
                    <div class="x_content">
                        {{--<input type="hidden" value="3" name="request_type_id" id="request_type_id">--}}
                        <table class="table table-bordered mytable"  dir="rtl" >
                            <thead>
                            <tr>

                                <th >کد هزینه</th>
                                <th >شرح</th>
                                <th >دفتر معین</th>
                                <th >اصل مبلغ</th>
                                <th >کسور</th>
                                <th >مبلغ پرداختی</th>
                                <th >صفحه</th>
                                <th >ردیف</th>


                            </tr>
                            </thead>
                            <tbody>
                            <tr id="oldRow">
                                <td ><input type='number'   id="code"  class='form-control'  ></td>
                                <td class="col-md-3"><input type='text'      id="description"  class='form-control'  ></td>
                                <td ><input type='number'   id="moeinOffice"  class='form-control'   ></td>
                                <td ><input type='number'   id="generalPrice" class='form-control'   ></td>
                                <td ><input type='number'   id="deduction" class='form-control'   ></td>
                                <td ><input type='number'   id="payedPrice" class='form-control' ></td>
                                <td ><input type='number'   id="page" class='form-control'   ></td>
                                <td ><input type='number'   id="row" class='form-control'   ></td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <div class="col-md-8">
                                <button id="addRow" type="button"
                                        class="btn btn-primary col-md-6 col-md-offset-6"> به لیست اضافه شود
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-md-2 col-sm-2 col-xs-12"></div>
            </div>
            <div class="x_panel">
                <div class="x_title">
                    <h2> درخواست نهایی
                    </h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="col-md-12 col-sm-8 col-xs-12">
                    <div class="x_content">
                        <form class="form-horizontal form-label-left product" novalidate id="myForm">
                            {!! csrf_field() !!}
                            <input type="hidden" id="requestId" name="requestId" value="{{$id}}">
                            <input type="hidden" value="0" name="recordCount" id="recordCount">

                            <table class="table table-bordered mytable" dir="rtl">
                                <thead>
                                <tr>
                                    <th >کد هزینه</th>
                                    <th >شرح</th>
                                    <th >دفتر معین</th>
                                    <th >اصل مبلغ</th>
                                    <th >کسور</th>
                                    <th >مبلغ پرداختی</th>
                                    <th >صفحه</th>
                                    <th >ردیف</th>
                                    <th class="col-md-1">حذف</th>
                                </tr>
                                </thead>
                                <tbody id="change">


                                </tbody>

                            </table>

                        </form>
                        <div class="form-group">
                            <div class="col-md-8">
                                <button style="display: none;"  type="button" id="save" name="save" type="button" class="btn btn-success col-md-6 col-md-offset-6"> ثبت نهایی
                                </button>
                            </div>
                        </div>

                    </div>

                </div>
                {{--! end tables --}}
            </div>
        </div>


        <script>
            var recordCount = 0;
            $(document).on('click','#addRow',function () {
                var code = $('#code').val();
                var description = $('#description').val();
                var moeinOffice = $('#moeinOffice').val();
                var generalPrice = $('#generalPrice').val();
                var deduction = $('#deduction').val();
                var payedPrice = $('#payedPrice').val();
                var page = $('#page').val();
                var row = $('#row').val();
                if(code == '' || code == null)
                {
                    $('#code').focus();
                    $('#code').css('border-color','red');
                    return false;

                }
                else if(description == '' || description == null)
                {
                    $('#description').focus();
                    $('#description').css('border-color','red');
                    return false;
                }
                else if(moeinOffice == '' || moeinOffice == null)
                {
                    $('#moeinOffice').focus();
                    $('#moeinOffice').css('border-color','red');
                    return false;
                }
                else if(generalPrice == '' || generalPrice == null)
                {
                    $('#generalPrice').focus();
                    $('#generalPrice').css('border-color','red');
                    return false;
                }
                else if(deduction == '' || deduction == null)
                {
                    $('#deduction').focus();
                    $('#deduction').css('border-color','red');
                    return false;
                }
                else if(payedPrice == '' || payedPrice == null)
                {
                    $('#payedPrice').focus();
                    $('#payedPrice').css('border-color','red');
                    return false;
                }
                else if(page == '' || page == null)
                {
                    $('#page').focus();
                    $('#page').css('border-color','red');
                    return false;
                }
                else if(row == '' || row == null)
                {
                    $('#row').focus();
                    $('#row').css('border-color','red');
                    return false;
                }
                else
                    {
                        recordCount++;
                        $('#recordCount').val(recordCount);
                        $('#change').append
                        (
                            "<tr>"+
                            "<td ><input type='number'    value='"+ code +"' class='form-control required'  name='code[]'></td>"+
                            "<td  class='col-md-3'><input type='text'      value='"+description+"' class='form-control required'  name='description[]' ></td>"+
                            "<td ><input type='number'    value='"+moeinOffice+"' class='form-control required'  name='moeinOffice[]' ></td>"+
                            "<td ><input type='number'   value='"+generalPrice+"' class='form-control required'  name='generalPrice[]' ></td>"+
                            "<td ><input type='number'   value='"+deduction+"' class='form-control required'  name='deduction[]' ></td>"+
                            "<td ><input type='number'   value='"+payedPrice+"' class='form-control required'  name='payedPrice[]'></td>"+
                            "<td ><input type='number'   value='"+page+"' class='form-control required'  name='page[]' ></td>"+
                            "<td ><input type='number'   value='"+row+"'  class='form-control required'  name='row[]' ></td>"+
                            "<td ><a type='button' class='btn btn-danger remove_row' data-toggle='tooltip' title='حذف' style='font-size:18px;'><span class='fa fa-trash'></span></a></td>"+
                            "</tr>"

                        );
                        $("#oldRow").children("td").children("input").each(function(){
                            $(this).val('');
                        });
                        $('#save').css('display','block');
                    }

            });
        </script>

        <script>
            $(document).on('click','.remove_row', function(){
                var recordCount = $('#recordCount').val();
                $(this).closest('tr').remove();
                recordCount--;
                $('#recordCount').val(recordCount);
                if(recordCount == 0)
                {
                    $('#save').css('display','none');
                }

            });
        </script>

        <script>

            $(document).on('click','#save',function(){
                var formData = $('#myForm').serialize();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $.ajax
                ({
                    cache : false,
                    url: "{{url('admin/saveCostDocument')}}",
                    type : "post",
                    data : formData,
                    beforeSend:function () {
                        var counter = 0;
                        $(".required").each(function() {
                            if ($(this).val() === "") {
                                $(this).css("border" , "red 4px solid");
                                counter++;
                            }
                        });
                        if(counter > 0){
                            swal({
                                title: "",
                                text:'تعدادی از فیلدهای فرم خالی است.لطفا فیلدها را پر نمایید سپس ثبت سند را بزنید' ,
                                type: "info",
                                confirmButtonText: "بستن"
                            });
                            return false;
                        }
                    },
                    success: function(response)
                    {
                        swal({
                            title: "",
                            text: response,
                            type: "info",
                            confirmButtonText: "بستن"
                        });
                        setTimeout(function(){ window.location.reload(true); }, 1000);
                    },error: function (error) {
                        if (error.status === 422) {
                            var x = error.responseJSON;
                            var errorsHtml = '';
                            // var count = 0;
                            $.each(x, function (key, value) {
                                errorsHtml += value[0] + '\n'; //showing only the first error.
                            });
                            //console.log(count)
                        swal({
                            title: "",
                            text: errorsHtml,
                            type: "warning",
                            confirmButtonText: "بستن"
                        });
                        }
                        if(error.status === 500)
                        {
                            console.log(error);
                            swal({
                                title: "",
                                text: "خطایی رخ داده است ، لطفا با بخش پشتیبانی تماس بگیرید",
                                type: "warning",
                                confirmButtonText: "بستن"
                            });
                        }
                    }
                });
            })
        </script>



@endsection
