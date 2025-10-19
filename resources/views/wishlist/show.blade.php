@extends('layouts.app')

@section('content')
<div class="container">
    <h1>My Wishlist</h1>
    @if($wishlist->isEmpty())
        <p>Your wishlist is empty.</p>
    @else
        <div id="cards">
            @foreach($wishlist as $item)
                <article class="card" id="{{ $item->product_id }}">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h2 class="card-title">{{ $item->product->name }}</h2>
                            <p class="card-text"><strong>Price:</strong> ${{ $item->product->price }}</p>
                            <p class="card-text">{{ $item->product->description }}</p>
                            <div style="display: flex; gap: 45px;">
                                <a href="{{ route('product.show', $item->product->product_id) }}" class="button">View Product</a>
                                <form action="{{ route('wishlist.remove', $item->product_id) }}" method="POST" class="d-inline ms-4">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: #d61818d0;">Remove</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
    <form action="{{ url()->previous() }}" method="get">
        <button type="submit" class="">Back</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('form[action*="wishlist/remove"]').forEach(form => {
            form.addEventListener('submit', function (event) {
                event.preventDefault();
                const productId = this.closest('article.card').id;
                fetch(this.action, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                }).then(response => {
                    if (response.ok) {
                        document.getElementById(productId).remove();
                    } else {
                        alert('Failed to remove the product from the wishlist.');
                    }
                }).catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
            });
        });
    });
</script>

@endsection
