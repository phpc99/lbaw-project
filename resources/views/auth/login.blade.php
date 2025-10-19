@extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('login') }}">
    {{ csrf_field() }}

    <label for="email">E-mail*</label>
    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="E-mail">
    @if ($errors->has('email'))
        <span class="error">
          {{ $errors->first('email') }}
        </span>
    @endif

    <label for="password" >Password*</label>
    <input id="password" type="password" name="password" required placeholder="Password">
    @if ($errors->has('password'))
        <span class="error">
            {{ $errors->first('password') }}
        </span>
    @endif

    <!--<label>
        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
    </label>-->

    <button type="submit">
        Login
    </button>
    <a class="button button-outline" href="{{ route('register') }}">Register</a>
    <p><a href="{{ url('login/google') }}"><img src="{{ asset('images/general_icons/googleIcon.png') }}" alt="Login with Google" style="width: 70px;"></a></p>
    <p><a href="{{ route('password.recover.form') }}">Forgot password</a></p>
    @if (session('success'))
        <p class="success">
            {{ session('success') }}
        </p>
    @endif
</form>
@endsection