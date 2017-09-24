@extends('layouts.adminLayout')
@section('content')
    <div class="page-title">
        <div class="title_right">
            <h3>
                درخواست خرید خدمت
            </h3>
        </div>
    </div>
    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2> فرم شماره 2

                    </h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                        {{--<li class="dropdown">--}}
                        {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"--}}
                        {{--aria-expanded="false"><i class="fa fa-wrench"></i></a>--}}
                        {{--<ul class="dropdown-menu" role="menu">--}}
                        {{--<li><a href="#">Settings 1</a>--}}
                        {{--</li>--}}
                        {{--<li><a href="#">Settings 2</a>--}}
                        {{--</li>--}}
                        {{--</ul>--}}
                        {{--</li>--}}
                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                {{-- table --}}
                <div class="col-md-10 col-sm-8 col-xs-12 col-md-offset-1">
                    <div class="x_content">
                        <form class="form-horizontal form-label-left" novalidate >
                            <table class="table table-bordered mytable" dir="rtl">
                                <thead>
                                <tr>
                                    <th class="col-md-4">خدمت</th>
                                    <th>برآورد مقدار</th>
                                    <th>توضیحات</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    {{ csrf_field() }}
                                    <td><input id="title" name="title" class="form-control col-md-7 col-xs-12" placeholder="" required="required" type="text" /></td>
                                    <td><input id="count" name="count" class="form-control col-md-7 col-xs-12" placeholder="" required="required" type="text" /></td>
                                    <td><input id="description" name="description" class="form-control col-md-7 col-xs-12" placeholder="" required="required" type="text" style="width: 400px;" /></td>
                                    <input type="hidden" id="token" name="csrf-token" value="{{ csrf_token() }}">
                                </tr>
                                </tbody>
                            </table>
                            <div class="form-group">
                                <div class="col-md-8">
                                    <button id="add_to_list" type="button"
                                            class="btn btn-primary col-md-7 col-md-offset-5"> به لیست اضافه شود
                                    </button>
                                </div>
                            </div>
                        </form>
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
                        <form id="service" novalidate>
                            {{ csrf_field() }}
                            <input type="hidden" id="token" name="csrf-token" value="{{ csrf_token() }}">
                            <table class="table table-bordered mytable" dir="rtl">
                                <thead>
                                <tr>
                                    <th>ردیف</th>
                                    <th>عنوان خدمت</th>
                                    <th>مقدار</th>
                                    <th>توضیحات</th>
                                    <th>حذف</th>
                                </tr>
                                </thead>

                                <tbody id="tbody">

                                </tbody>

                            </table>

                            <div class="form-group">
                                <div class="col-md-8">
                                    <button id="send" type="button" class="btn btn-success col-md-6 col-md-offset-6"> ثبت
                                        نهایی
                                    </button>
                                </div>
                            </div>
                        </form>


                    </div>
                </div>
                {{--! end tables --}}
            </div>
        </div>

    <script>
        /**
         * Created by Arash on 9/15/2017.
         */

        var R = 0;
        $('#add_to_list').click(function () {

            var title = $('#title').val();
            var count = $('#count').val();

            if(title == '' || title == null)
            {
                $('#title').focus();
                $('#title').css('border-color','red');
                return false;
            }

            else if(count == '' || count == null)
            {
                $('#count').focus();
                $('#count').css('border-color','red');
                return false;
            }
            else if(title != '' && title!= null && count!= '' && count!= null) {



                R++;
                $('#tbody').append
                (
                    "<tr>" +
                    "<th scope='row'>" + R + "</th>" +
                    "<td><input style='padding-right:5px;' class='form-control required' type='text' id='title' name='title[]'  value=' " + $('#title').val() + " '></td>" +
                    "<td><input style='padding-right:5px;' class='form-control required' type='text' id='count' name='count[]'  value=' " + $('#count').val() + " '></td>" +
                    "<td><input id='description' name='description[]' class='form-control col-md-7 col-xs-12' placeholder=''    value='"+$('#description').val()+ "'  required='required' type='text' style='width: 400px;' /></td>"+
                    "<td><a type='button' class='btn btn-danger remove_row' data-toggle='tooltip' title='حذف'><span class='fa fa-trash-o'></span></a></td>" +
                    "</tr>"
                );

                //two lines code below is to make title's text box and count's text box empty if needed....
                $('#title').val('');
                $('#count').val('');
                $('#description').val('');

                $(document).on('click', '.remove_row', function () {
                    $(this).closest('tr').remove();
                });

            }

        });


        $('#send').click(function(){

            // alert('hello');

            var title1 = $('#title1').val();
            var count1 = $('#count1').val();

            var formData = $('#service').serialize();


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });

            $.ajax
            ({

                url: "{{Url('user/sendService')}}",
                type: "POST",
                //dataType:'JSON',
                data:formData,
                beforeSend:function () {
                    var counter = 0;
                    $(".required").each(function() {
                        if ($(this).val() === "") {
                            $(this).css("border-color" , "red");
                            counter++;
                        }
                    });
                    if(counter > 0){
                        swal
                        ({
                            title: '',
                            text: 'تعدادی از فیلدهای فرم خالی است.لطفا فیلدها را پر نمایید سپس ثبت نهایی را بزنید',
                            type:'warning',
                            confirmButtonText: "بستن"
                        });
                        return false;
                    }
                },
                success:function(response)
                {
                    swal
                    ({
                        title: '',
                        text: response,
                        type:'info',
                        confirmButtonText: "بستن"
                    });
                    console.log(response);
                },error:function (error)
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

            })

        });

    </script>

@endsection
