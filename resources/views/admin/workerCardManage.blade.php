@extends('layouts.adminLayout')
@section('content')
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2> مدیریت کارت های کارگری</h2>
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
                <form id="myForm">
                    <div class="container">
                        {{ csrf_field() }}
                        <div class="col-md-3 col-sm-3 col-xs-12 form-group pull-right">
                            <input type="text" class="form-control" style="text-align:right;" id="date1"
                                   name="date1" placeholder="از تاریخ" min="1" max="5">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12 form-group pull-right">
                            <input type="text" class="form-control" style="text-align:right;" id="date2"
                                   name="date2" placeholder=" تا تاریخ" min="1" max="5">
                        </div>
                        <input type="hidden" id="token" value="{{ csrf_token() }}">
                        <button id="search" type="button" class="col-md-2 btn btn-info" style="font-weight: bold; margin-left: 30%">
                            جستجو
                        </button>
                    </div>
                </form>
                <div class="x_content">
                    <table style="direction:rtl;text-align: center;font-size: 17px;" id="example"
                           class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                        <input type="hidden" id="token" value="{{ csrf_token() }}">
                        <thead>
                        <tr>
                            <th style="text-align: center">شناسه</th>
                            <th style="text-align: center">نام و نام خانودگی صادر کننده</th>
                            <th style="text-align: center"> نام و نام خانوادگی کارگر</th>
                            <th style="text-align: center">تاریخ درج</th>
                            <th style="text-align: center;border-right: 1px solid #e0e0d1">نمایش کارت</th>
                        </tr>
                        </thead>

                        <tbody id="change">
                        @foreach($workers as $worker)
                            <tr class="unit">
                                <td class="col-md-1">
                                    {{$worker->id}}
                                </td>
                                <td id="adminName">
                                    {{$worker->user->name.chr(10).$worker->user->family}}
                                </td>
                                <td>
                                    {{$worker->name.chr(10).$worker->family}}
                                </td>
                                <td>
                                    {{$worker->date}}
                                </td>
                                <td style="border-right: 1px solid #e0e0d1" class="statusUser" id="">
                                    <a class="btn btn-success" href="{{URL::asset('admin/showWorkerCard')}}/{{$worker->id}}">نمایش کارت</a>
                                </td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        {{--edit user's status by user-id --}}
        <script src="{{URL::asset('public/js/persianDatepicker.js')}}"></script>
        <script>
            $('#date1').persianDatepicker();
            $('#date2').persianDatepicker();
        </script>

        <script>


            $(document).on('click','#search',function () {

                var date1=$('#date1').val();
                var date2=$('#date2').val();
                var token=$('#token').val();
                var name =$('#adminName').text();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                })
                $.ajax
                ({

                    url:"{{Url('admin/searchOnDate')}}/{{1}}",
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

//                        var type=typeof(response);
//                        alert(type);
//                        return false;
                        console.log(response);

                        var len = response.data;//rayat
                        var resLen = response.length;//rayat
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
                        }
                        if(resLen == 52)//rayat
                        {
                            swal({
                                title: "",
                                text: response,
                                type: "warning",
                                confirmButtonText: "بستن"
                            });
                            return false;//rayat
                        }else
                            {
                                if(typeof (response) == "object")
                                {
                                    $('#change').empty();
                                    $.each(response.data,function(key,value) {
                                        $('#change').append(
                                            "<tr   class='unit'>" +
                                            "<td   id='date'>" + value.id+ "</td>" +
                                            "<td   id='date'>" + name + "</td>" +
                                            "<td   id='date'>" + value.name + ' ' + value.family + "</td>" +
                                            "<td   id='time1'>" + value.date + "</td>" +
                                            "<td   id='time2'><a class='btn btn-success' href='{{URL::asset("admin/showWorkerCard")}}/"+value.id+"'>نمایش عکس کارت</a></td>"+
                                            "</tr>");

                                    });
                                }

                            }

                    },error:function (error) {

                        swal({
                            title: "",
                            text: error.content,
                            type: "warning",
                            confirmButtonText: "بستن"
                        });
                        //console.log(error);
                    }

                });

            });
        </script>
@endsection