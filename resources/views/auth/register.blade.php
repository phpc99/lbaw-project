@extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('register') }}">
    {{ csrf_field() }}

    <label for="name">Name*</label>
    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="Name">
    @if ($errors->has('name'))
      <span class="error">
          {{ $errors->first('name') }}
      </span>
    @endif

    <label for="phone_number">Phone number</label>
    <div style="display: flex; gap: 10px; align-items: flex-start;">
        <!-- Símbolo + fora da caixa de texto -->
        <span style="font-size: 18px; font-weight: bold; margin-top: 5px;">+</span>
        <input id="country_code" type="number" name="country_code" value="{{ old('country_code') }}" required 
               min="111" max="999" 
               style="width: 60px; appearance: textfield;" placeholder="Code" maxlength="3" oninput="validatePrefix()">
        <input id="phone_number" type="number" name="phone_number" value="{{ old('phone_number') }}" required 
               min="111111111" max="999999999" 
               style="flex-grow: 1; appearance: textfield;" placeholder="Phone number" maxlength="9" oninput="validatePhone()">
    </div>
    @if ($errors->has('country_code'))
      <span class="error">
          {{ $errors->first('country_code') }}
      </span>
    @endif
    @if ($errors->has('phone_number'))
      <span class="error">
          {{ $errors->first('phone_number') }}
      </span>
    @endif

    <label for="email">E-Mail Address*</label>
    <input id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="E-mail">
    @if ($errors->has('email'))
      <span class="error">
          {{ $errors->first('email') }}
      </span>
    @endif

    <label for="password">Password*</label>
    <input id="password" type="password" name="password" required placeholder="Password">
    @if ($errors->has('password'))
      <span class="error">
          {{ $errors->first('password') }}
      </span>
    @endif

    <label for="password-confirm">Confirm password*</label>
    <input id="password-confirm" type="password" name="password_confirmation" required placeholder="Confirm password">

    <button type="submit">
      Register
    </button>
    <a class="button button-outline" href="{{ route('login') }}">Login</a>
</form>

<script>
    function validatePrefix() {
        const prefixInput = document.getElementById('country_code');
        if (prefixInput.value.length > 3) {
            prefixInput.value = prefixInput.value.slice(0, 3); 
        }
    }

    function validatePhone() {
        const phoneInput = document.getElementById('phone_number');
        if (phoneInput.value.length > 9) {
            phoneInput.value = phoneInput.value.slice(0, 9);
        }
    }
</script>

@endsection