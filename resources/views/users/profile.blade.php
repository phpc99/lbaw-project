@extends('layouts.app')

@section('content')
<div class="container">
    <div class="profile-first-line">
        <h1>Welcome, {{ $user->name }}!</h1>
        <button id="toggle-edit-form" style="background: none; border: none">
            <img src="{{ asset('images/general_icons/edit_icon2.png') }}" alt="Edit Profile" class="hover-effect-icon" style="width: 38px; height: 38px;">
        </button>
        <form id="edit-form" action="{{ route('user.update') }}" method="POST" enctype="multipart/form-data" style="display: none;">
            @csrf
            @method('PUT')
            <div class="edit-form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="{{ $user->name }}" required placeholder="Update your name">
            </div>
            <div class="edit-form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="{{ $user->email }}" required placeholder="Update your email">
            </div>
            <div class="edit-form-group phone-input">
                <label for="phone_number">Phone Number:</label>
                <div class="phone-prefix">+</div>
                <input type="text" id="ddd" name="ddd" value="{{ substr($user->phone_number, 1, 3) }}" maxlength="3" placeholder="DDD" required>
                <input type="text" id="phone_number" name="phone_number" value="{{ substr($user->phone_number, 4) }}" maxlength="9" placeholder="XXXXXXXXX" required>
            </div>
            <div class="edit-form-group">
                <label for="picture">Profile Picture:</label>
                <input type="file" id="picture" name="picture" accept="image/*">
            </div>
            <div class="edit-form-actions">
                <button type="submit">Save Changes</button>
                <button type="button" id="cancel-edit">Cancel</button>
            </div>
        </form>
    </div>
    <img src="{{ asset($user->picture ?? 'images/general_icons/user_icon.png') }}" alt="{{ $user->name }}" class="user-icon" style="width: 200px; height: 200px; border-radius: 50%; object-fit: cover;">
    <p>Email: {{ $user->email }}</p>
    <p>Phone Number: +{{ substr($user->phone_number, 1, 3) }} {{ substr($user->phone_number, 4) }}</p>
    <p>Points: {{ $user->points }}</p>

    <div class="profile-actions">
        @if (Auth::check())
            <a class="button" href="{{ route('logout') }}"> Logout </a>
        @endif
        @if (auth()->check() && !auth()->user()->isAdmin())
            <a class="button" href="{{ route('purchase.list') }}">Purchase History</a>
        @endif
        @if (auth()->check())
            <form action="{{ route('account.delete') }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete your account? This action is irreversible.');">
                @csrf
                @method('DELETE')
                <button id="remove-button" type="submit" class="btn-danger" style="background: none; border: none">
                    <img src="{{ asset('images/general_icons/delete_icon2.png') }}" alt="Remove Profile" class="hover-effect-icon2" style="width: 35px; height: 35px;">
                </button>
            </form>
        @endif
    </div>

    @if (Auth::user() && Auth::user()->permissions === 'admin')
        <p>You are an administrator. Enjoy your privileges.</p>
    @endif

</div>

<script type="text/javascript" src={{ url('public/js/app.js') }} defer></script>

@endsection