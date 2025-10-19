@extends('layouts.app')

@section('content')

<form method="POST" action="{{ route('password.email') }}">
    @csrf

    <p>The link to reset password will be send to your email address.</p>

    <p>Please provide the email which you logged in.</p>

    <label for="email">E-mail</label>
    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="E-mail">
    @if ($errors->has('email'))
        <span class="error">
          {{ $errors->first('email') }}
        </span>
    @endif

    <button type="submit">
        Send E-mail
    </button>

    @if (session('success'))
        <p class="success">
            {{ session('success') }}
        </p>
    @endif
</form>

@endsection