@extends('layouts.app')

@section('content')

    @forelse ($news as $new)
        <div id="cards">
            <article class="card">
                <header>
                    <h2>{{ $new->title }}</h2>
                </header>
                <p>{{ $new->content }}</p>
                <footer>
                    <a href="{{ route('user.show', $new->users_id) }}" class="button">View Profile</a>
                </footer>
            </article>
        </div>
    @empty
        <p>No new customers activity.</p>
    @endforelse

@endsection