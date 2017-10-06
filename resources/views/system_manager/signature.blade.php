@extends('layouts.adminLayout')
@section('content')
    {{--<input type="hidden" value="{{$user_id=\Illuminate\Support\Facades\Auth::user()->id}}">--}}
    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2> مدیریت امضاها
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

                <div class="col-md-12 col-sm-8 col-xs-12" style="font-size: 16px;">
                    <div class="x_content">
                        <table style="direction:rtl;text-align: center" id="datatable-responsive"
                               class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                               width="100%">
                            <thead>
                            <tr>
                                <th style="text-align: center" class="col-md-1">شناسه</th>
                                <th style="text-align: center" class="col-md-2">مالک امضاء</th>
                                <th style="text-align: center" class="col-md-2">سمت</th>
                                <th style="text-align: center" class="col-md-2">نوع امضاء</th>
                                {{--<th style="text-align: center">تصویر</th>--}}
                                {{--<th style="text-align: center">تصویر کاربری</th>--}}
                                <th class="col-md-1" style="text-align: center">عملیات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($signatures as $signature)
                                <tr>
                                    <td>{{$signature->id}}</td>
                                    <td>{{$signature->user->name}} {{$signature->user->family}}</td>
                                    <td>{{$signature->unit->title}}</td>
                                    <td style="font-size:20px;">
                                        @if($signature->forced==1)
                                        <span class="label label-danger col-md-12">اجباری</span>
                                        @else
                                        <span class="label label-info col-md-12">اختیاری</span>
                                    @endif
                                    </td>
                                    {{--<td><img class="col-md-2" src="http://s9.picofile.com/file/8306682392/blesing_cover_.jpg" style="padding:0;"/></td>--}}
                                    <td>
                                        <a href="{{URL::asset('systemManager/showSignature')}}/{{$signature->id}}" target="_blank" type="button"
                                           class="btn btn-round btn-default" data-toggle="tooltip" title="نمایش امضا">
                                            <span class="fa fa-search"></span>
                                        </a>
                                        @if($signature->forced == 1)
                                        <a  content="{{$signature->id}}" name="تبدیل به اختیاری" id="change"  class="btn btn-round btn-success" data-toggle="tooltip" title="تبدیل به اختیاری">
                                            <span class="glyphicon glyphicon-refresh"></span>
                                        </a>
                                        @endif
                                        @if($signature->forced == 0)
                                            <a  content="{{$signature->id}}" name="تبدیل به اجباری" id="change"  class="btn btn-round btn-success" data-toggle="tooltip" title="تبدیل به اجباری">
                                                <span class="glyphicon glyphicon-refresh"></span>
                                            </a>


                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                        </table>
                    </div>
                </div>
                <div class="col-md-2 col-sm-2 col-xs-12"></div>
            </div>
        </div>

        <script>
            $(document).on('click','#change',function () {
               var signatureId = $(this).attr('content');
               var token       = $('#token').val();
               var parent      = $(this).parent();
               var status      = $(this).attr('name');
               var td          = $(this);



               if(status == 'تبدیل به اجباری')
               {
                   $.ajaxSetup({
                       headers: {
                           'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                       }
                   });

                   $.ajax
                   ({

                       url     : "{{Url('systemManager/makeSignatureForced')}}",
                       type    : "post",
                       data    : {'signatureId':signatureId ,'_token':token},
                       context : {'parent':parent,'td':td},
                       success : function (response) {
                           $(parent).prev().empty();
                           $(td).replaceWith
                           (
                               "<a  content=' "+ signatureId  +" ' name='تبدیل به اختیاری' id='change'  class='btn btn-round btn-success'  title='تبدیل به اختیاری'>"+
                                    "<span class='glyphicon glyphicon-refresh'></span>"+
                               "</a>"
                           );
                           //$(td).name  = 'تبدیل به اختیاری';
                           $(parent).prev().append(  "<span class='label label-danger col-md-12'>اجباری</span>" );
                           swal({
                               title: "",
                               text: "وضعیت امضاء از اختیاری به اجباری تغییر یافت",
                               type: "info",
                               confirmButtonText: "بستن"
                           });
                       },error : function()
                       {
                           swal({
                               title: "",
                               text: 'خطایی رخ داده است ، لطفا با بخش پشتیبانی تماس بگیرید',
                               type: "warning",
                               confirmButtonText: "بستن"
                           });
                       }
                   })


               }
               if(status == 'تبدیل به اختیاری')
               {
                   $.ajaxSetup({
                       headers: {
                           'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                       }
                   });

                   $.ajax
                   ({

                       url     : "{{Url('systemManager/makeSignatureUnforced')}}",
                       type    : "post",
                       data    : {'signatureId':signatureId ,'_token':token},
                       context : {'parent':parent,'td':td},
                       success : function (response) {
//                           $(td).title = "تبدیل به اجباری";
//                           $(td).name  = 'تبدیل به اجباری';
                           $(td).replaceWith
                           (
                               "<a  content=' "+ signatureId  +" ' name='تبدیل به اجباری' id='change'  class='btn btn-round btn-success'  title='تبدیل به اجباری'>"+
                               "<span class='glyphicon glyphicon-refresh'></span>"+
                               "</a>"
                           );
                           $(parent).prev().empty();
                           $(parent).prev().append(  "<span class='label label-info col-md-12'>اختیاری</span>" );
                           swal({
                               title: "",
                               text: "وضعیت امضاء از اجباری  به اختیاری تغییر یافت",
                               type: "info",
                               confirmButtonText: "بستن"
                           });
                       },error : function()
                       {
                           swal({
                               title: "",
                               text: 'خطایی رخ داده است ، لطفا با بخش پشتیبانی تماس بگیرید',
                               type: "warning",
                               confirmButtonText: "بستن"
                           });
                       }
                   })
               }

            });
        </script>
@endsection
