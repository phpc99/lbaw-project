@extends('layouts.app')

@section('content')
    <div class="container">
        <!--<img src="{{$user->image}}" alt="{{$user->name}}" style="width: 100%; max-width: 300px;">-->
        <h1>{{ $user->name }}</h1>
        <p>Email: {{ $user->email }}</p>
        <p>Phone Number: {{ $user->phone_number }}</p>
        <p>Points: {{ $user->points }}</p>

        @if (Auth::user() && Auth::user()->isAdmin())
            <div class="admin-actions">
                <form action="{{ route('user.delete', $user->user_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently remove this user? This action cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Remove user</button>
                </form>
            </div>
        @endif
    </div>
@endsection