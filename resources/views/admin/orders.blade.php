@extends('layouts.app')

@section('content')
    <div id="cards">
        @forelse ($news as $new)
            <article class="card">
                <header>
                    <h2>{{ $new->title }}</h2>
                </header>
                <p>{{ $new->content }}</p>
                <footer style="background: none">
                    <a href="{{ route('user.show', $new->users_id) }}" class="button">View Profile</a>
                </footer>
            </article>
        @empty
        <p>No new purchases.</p>
        @endforelse
    </div>

    <div class="order-actions">
        <a class="button" href="{{ route('orders.show') }}">Pending Orders</a>
        <a class="button" href="{{ route('admin.purchase.history') }}">Global purchase History</a>
    </div>
    
@endsection