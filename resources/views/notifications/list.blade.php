@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Notifications</h1>
        @if ($notifications->isNotEmpty())
            <ul>
                @foreach ($notifications as $notification)
                    @if (!$notification->is_read)
                        <div class="notification-item {{ $notification->is_read ? 'read' : 'unread' }}">
                            <p>{{ $notification->getMessage() }}</p>
                            <form action="{{ route('notifications.markAsRead', $notification->notification_id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit">Mark as Read</button>
                            </form>
                        </div>
                    @endif
                @endforeach
            </ul>
        @else
            <p>Nothing new for now.</p>    
        @endif
        <form action="{{ url()->previous() }}" method="get">
            <button type="submit" class="">Back</button>
        </form>

    </div>
@endsection