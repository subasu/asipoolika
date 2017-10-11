@extends('layouts.app')
@section('content')
    <style>
        .state {
            font-family: BNazanin;
        }
        @font-face
        {
            font-family:'dotsfont';
            src:url('public/dashboard/fonts/dotsfont.eot');
            src:url('public/dashboard/fonts/dotsfont.eot?#iefix')  format('embedded-opentype'),
            url('public/dashboard/fonts/dotsfont.svg#font')    format('svg'),
            url('public/dashboard/fonts/dotsfont.woff')        format('woff'),
            url('public/dashboard/fonts/dotsfont.ttf')         format('truetype');
            font-weight:normal;
            font-style:normal;
        }
    </style>
    <form class="login form-horizontal col-md-3 col-sm-7 col-xs-7" role="form" method="POST"
          action="{{ url('/login') }}" autocomplete="off">
        {{ csrf_field() }}
        <p class="title">صفحه ی ورود</p>
        <input type="text" placeholder="نام کاربری" id="email" autofocus name="email" value="{{ old('email') }}"
               class=""/>
        <i class="fa fa-user"></i>

        @if ($errors->has('email'))
            <span class="help-block">
                <strong>{{ $errors->first('email') }}</strong>
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
    <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    <script>

        $(document).on('keydown', "#password", function () {
            $("#password").css({"font-family": "dotsfont", "font-size": "12px"});
        });

    </script>
@endsection
