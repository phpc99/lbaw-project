@extends('layouts.app')

@section('content')

<form method="POST" action="{{ route('password.reset') }}">
    @csrf

    <input type="hidden" value="{{ $email }}" name="email">

    <p>Reseting password for {{ $email }}</p>

    <label for="password">New Password</label>
    <input id="password" type="password" name="password" value="{{ old('password') }}" required autofocus placeholder="Password">
    @if ($errors->has('password'))
        <span class="error">
          {{ $errors->first('password') }}
        </span>
    @endif

    <label for="password-confirmation">Confirm Password</label>
    <input id="password-confirmation" type="password" name="password_confirmation" value="{{ old('password_confirmation') }}" required placeholder="Password Confirmation">
    @if ($errors->has('password-confirmation'))
        <span class="error">
          {{ $errors->first('password-confirmation') }}
        </span>
    @endif

    <button type="submit">
        Reset Password
    </button>

    @if (session('success'))
        <p class="success">
            {{ session('success') }}
        </p>
    @endif
</form>

@endsection