@extends('layouts/app')

@section('content')
    <div class="container">
        <h1>Search Results for "{{ $query }}"</h1>

        @if ($type === 'products') 
            <div id="cards">
                @forelse ($results as $product)
                    <article class="card">
                        <header>
                            <h2>{{ $product->name }}</h2>
                        </header>
                        <p>{{ $product->description }}</p>
                        <footer style="background: none">
                            <a href="{{ route('product.show', $product->product_id) }}" class="button">View Details</a>
                        </footer>
                    </article>
                @empty
                    <p>No products found.</p>
                @endforelse
            </div>

        @elseif ($type === 'users')
            <div id="cards">
                @forelse ($results as $user)
                    <article class="card">
                        <header>
                            <h2>{{ $user->name }}</h2>
                        </header>
                        <p>{{ $user->email }}</p>
                        <footer style="background: none">
                            <a href="{{ route('user.show', $user->user_id) }}" class="button">View Profile</a>
                        </footer>
                    </article>
                @empty
                    <p>No users found.</p>
                @endforelse
            </div>  
        @endif
    </div>
@endsection