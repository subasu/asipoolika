@extends('layouts.app')
@section('content')
 <div class="row">
     <div class="col-md-12" style="color:white;font-size: 40px;margin-bottom: 20px;font-family: IranNastaliq,'IranNastaliq';">
            شبکه بهداشت خمینی شهر
     </div>

 </div>
 <div class="row">
     <div class="col-md-4 col-sm-7 col-xs-7">
         <a target="blank" href="http://www.artansoftware.ir">
         <div class="row">
             <img src="{{URL(asset('public/login/img/logo.png'))}}" class="col-md-8" style="margin-left:15%">
         </div>
         <div class="row" style="margin-top: 10%">
             <p style="text-align:center;font-family: IranNastaliq,'IranNastaliq';font-size:45px;color:white;">گروه فنی مهندسی آرتان</p>
         </div>
             </a>
     </div>
     <form class="login form-horizontal col-md-4 col-sm-7 col-xs-7" role="form" method="POST"
           action="{{ url('/login') }}" autocomplete="off">
         {{ csrf_field() }}
         <p class="title">صفحه ی ورود</p>
         <input type="text" placeholder="نام کاربری" id="username" autofocus name="username" value="{{ old('username') }}" class=""/>
         <i class="fa fa-user"></i>
         @if ($errors->has('username'))
             <span class="help-block">
                <strong>{{ $errors->first('username') }}</strong>
            </span>
         @endif
         <input type="text" name="password" id="password" placeholder="رمزعبور"
                style="-webkit-text-security: disc;"
                class="{{ $errors->has('password') ? ' has-error' : '' }}"/>
         <i class="fa fa-key"></i>

         @if ($errors->has('password'))
             <span class="help-block">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
         @endif
         {{--        <a href="{{ url('/password/reset') }}">رمز عبور خود را فراموش کرده اید؟</a>--}}
         <button>
             {{--<i class="spinner"></i>--}}
             <span class="state">ورود</span>
         </button>
     </form>
     <div class="col-md-4 col-sm-7 col-xs-7">
         <div class="row">
             <img src="{{URL(asset('public/login/img/logo.png'))}}" class="col-md-8" style="margin-left:15%">
         </div>
         <div class="row" style="margin-top: 10%">
             <p style="text-align:center;font-family: IranNastaliq,'IranNastaliq';font-size:40px;color:white;">سیستم نرم افزار تدارکات «آماد»</p>
         </div>
     </div>
 </div>




    <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    <script>
        $(document).on('keydown', "#password", function () {
            $("#password").css({"font-family": "dotsfont", "font-size": "12px"});
        });
    </script>

@endsection
