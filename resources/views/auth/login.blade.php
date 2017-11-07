@extends('layouts.app')
@section('content')
    <form class="login form-horizontal col-md-3 col-sm-7 col-xs-7" role="form" method="POST"
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
        <input type="password" name="password" id="password" placeholder="رمزعبور"
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
    <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    <script>
        $(document).on('keydown', "#password", function () {
            $("#password").css({"font-family": "dotsfont", "font-size": "12px"});
        });
    </script>

@endsection
