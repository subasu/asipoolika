@extends('layouts.adminLayout')
@section('content')
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>

                        بررسی امور روزانه
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

                </div>


                </div>
                <div class="x_content">
                    <table style="direction:rtl;text-align: center" id="example"
                           class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                        <input type="hidden" id="token" value="{{ csrf_token() }}">
                        <thead>
                        <tr>
                            <th style="text-align: center" class="">شناسه</th>
                            <th style="text-align: center" class="">نوع درخواست</th>
                            <th style="text-align: center" class="">واحد درخواست دهنده</th>
                            <th style="text-align: center;" class="col-md-2">عملیات</th>
                        </tr>
                        </thead>
                        <tbody id="change">
                        @foreach($requests as $request)
                            <tr class="unit">
                                <td>
                                    {{$request->id}}
                                </td>
                                <td>
                                    {{$request->requestType->title}}
                                </td>
                                <td>
                                    {{$request->unit->title}}
                                </td>
                                @if($request->request_type_id == 2)
                                <td>
                                    <a href="{{url('admin/printServiceRequest/'.$request->id)}}" class="btn btn-primary">مشاهده جزئیات</a>
                                </td>
                                @endif
                                @if($request->request_type_id == 3)
                                    <td>
                                        <a href="{{url('admin/printProductRequest/'.$request->id)}}" class="btn btn-primary">مشاهده جزئیات</a>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <script src="{{URL::asset('public/js/persianDatepicker.js')}}"></script>
        <script>
            $('#date1').persianDatepicker();
            $('#date2').persianDatepicker();
        </script>
        <script>
            $(document).on('click','#search',function(){
                var date1 = $('#date1').val();
                var date2 = $('#date2').val();

                var token=$('#token').val();

                // alert(name);

//            alert(date1);
//            alert(date2);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                })
                $.ajax
                ({

                    url:"{{Url('user/searchOnDate')}}/{{1}}",
                    type:'post',
                    dataType:'json',
                    data:{'date1':date1,'date2':date2,'_token':token},
                    beforeSend:function()
                    {
                        if(date1 != "" && date2 !="")
                        {
                            //split method use to convert string to array
                            var date1arr = date1.split('/');
                            //alert (date1arr);
                            var year1= date1arr[0];
                            var month1= date1arr[1];
                            var day1= date1arr[2];

                            var date2arr = date2.split('/');
                            //alert(date2arr);
                            var year2 = date2arr[0];
                            var month2 = date2arr[1];
                            var day2 = date2arr[2];

                            var d1 = new Date(year1,month1,day1);
                            //alert(d1);
                            var d2 = new Date(year2,month2,day2);
                            //alert(d2);

                            if(d2 < d1)
                            {
                                swal({
                                    title: "هشدار",
                                    text: "لطفا بازه زمانی را بطور صحیح وارد نمایید",
                                    type: "warning",
                                    confirmButtonText: "بستن"
                                });
                                return false;
                            }

                        }else if(date1 == "")
                        {
                            $('#date1').focus();
                            $('#date1').css('border-color','red');
                            return false;
                        }
                        else if(date2 == "")
                        {
                            $('#date2').focus();
                            $('#date2').css('border-color','red');
                            return false;
                        }

                    },
                    success:function(response)
                    {
                        //alert(response.toSource());


                        console.log(response);

                        var len = response.data.length;
                        console.log(len);
                        if(len == '')
                        {
                            swal({
                                title: "",
                                text: "موردی یافت نشد",
                                type: "info",
                                confirmButtonText: "بستن"
                            });
                            return false;
                        }else
                        {
                            $('#change').empty();
                            $.each(response.data,function(key,value) {
                                if(value.active == 0)
                                {
                                    $('#change').append(

                                        "<tr   class='unit'>" +
                                        "<td   id='date'>" + value.id+ "</td>" +
                                        "<td   id='date'>" + value.unit.title + "</td>" +
                                        "<td   id='date'>" + value.title + "</td>" +
                                        "<td   id='date'>" + value.date + "</td>" +
                                        "<td   id='time1'><label  class='col-md-7 col-md-offset-3  label label-warning' style='margin-left: 10%; font-size: 120%;width: 80%; !important;'>در حال بررسی</label></td>" +
                                        "<td   id='time2'><a class='btn btn-success' target='_blank' href='{{URL::asset("user/ticketConversation")}}/"+value.id+" '>مشاهده جزییات</a></td>"+
                                        "</tr>");
                                }
                                if(value.active == 1)
                                {
                                    $('#change').append(

                                        "<tr   class='unit'>" +
                                        "<td   id='date'>" + value.id+ "</td>" +
                                        "<td   id='date'>" + value.unit.title + "</td>" +
                                        "<td   id='date'>" + value.title + "</td>" +
                                        "<td   id='date'>" + value.date + "</td>" +
                                        "<td   id='time1'><label  class='col-md-7 col-md-offset-3  label label-default' style='margin-left: 10%; font-size: 120%;width: 80%; !important;'>اتمام تیکت از طرف ادمین</label></td>" +
                                        "<td   id='time2'><a class='btn btn-success' target='_blank' href='{{URL::asset("user/ticketConversation")}}/"+value.id+" '>مشاهده جزییات</a></td>"+
                                        "</tr>");
                                }
                                if(value.active == 2)
                                {
                                    $('#change').append(

                                        "<tr   class='unit'>" +
                                        "<td   id='date'>" + value.id+ "</td>" +
                                        "<td   id='date'>" + value.unit.title + "</td>" +
                                        "<td   id='date'>" + value.title + "</td>" +
                                        "<td   id='date'>" + value.date + "</td>" +
                                        "<td   id='time1'><label  class='col-md-7 col-md-offset-3  label label-default' style='margin-left: 10%; font-size: 120%;width: 80%; !important;'>اتمام تیکت از طرف ادمین</label></td>" +
                                        "<td   id='time2'><a class='btn btn-success' target='_blank' href='{{URL::asset("user/ticketConversation")}}/"+value.id+" '>مشاهده جزییات</a></td>"+
                                        "</tr>");
                                }


                            });
                        }

                    },error:function (error) {

                        swal({
                            title: "",
                            text: "خطایی رخ داده است.لطفا با بخش پشتیبانی تماس بگیرید",
                            type: "info",
                            confirmButtonText: "بستن"
                        });
                        console.log(error);
                    }

                });

            });
        </script>
    {{--edit user's status by user-id --}}
@endsection