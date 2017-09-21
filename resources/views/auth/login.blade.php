@extends('layouts.app')
@section('content')
    <style>
        .state {
            font-family: BNazanin;
        }
    </style>
    <form class="login form-horizontal col-md-3 col-sm-7 col-xs-7" role="form" method="POST"
          action="{{ url('/login') }}" >
        {{ csrf_field() }}
        <p class="title">صفحه ی ورود</p>
        <input type="text" placeholder="نام کاربری" id="email" autofocus name="email" value="{{ old('email') }}" class=""/>
        <i class="fa fa-user"></i>

        @if ($errors->has('email'))
            <span class="help-block">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif

        <input type="password" name="password" id="password" placeholder="رمزعبور"
               class="{{ $errors->has('password') ? ' has-error' : '' }}"/>
        <i class="fa fa-key"></i>

        @if ($errors->has('password'))
            <span class="help-block">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
        @endif

        <a href="{{ url('/password/reset') }}">رمز عبور خود را فراموش کرده اید؟</a>

        <button>
            <i class="spinner"></i>
            <span class="state">ورود</span>
        </button>

    </form>
@endsection
