@extends('layouts.app')

@section('content')
<div class="products-container">
    <h1>Products in "{{ $category->name }}"</h1>

    <div id="cards">
        @foreach ($products as $product)
            <article class="card" style="position: relative;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div class="product-title" style="padding-right: 40px;"> <!-- Garantindo espaço para o botão -->
                        <h2>{{ $product->name }}</h2>
                    </div>

                    <!-- Botão "Adicionar à Wishlist" no canto superior direito -->
                    @if(Auth::check() && !$product->wishlistUsers->contains(Auth::user()))
                        <form action="{{ route('wishlist.add', $product->product_id) }}" method="POST" style="position: absolute; top: 16px; right: 16px;">
                            @csrf
                            <button class="add-to-wishlist" type="submit" style="padding: 10px; margin: 1px; display: flex; justify-content: center; align-items: center;">+</button>
                        </form>
                    @endif
                </div>

                <div class="product-details">
                    <p>Price: ${{ $product->price }}</p>
                    <p>{{ $product->description }}</p>
                </div>

                <div style="display: flex; gap: 20px;">
                    <!-- View Details Button -->
                    <a href="{{ route('products.show', $product->product_id) }}" class="button details-btn">View Details</a>

                    <!-- Add to Cart Button -->
                    <a href="#" class="button add-to-cart" onclick="event.preventDefault(); document.getElementById('add-to-cart-{{ $product->product_id }}').submit();">
                        Add to Cart
                    </a>
                    <form id="add-to-cart-{{ $product->product_id }}" 
                        action="{{ route('cart.add', $product->product_id) }}" 
                        method="POST" 
                        style="display: none;">
                        @csrf
                    </form>
                </div>
            </article>
        @endforeach
    </div>

    <div class="pagination-container">
        {{ $products->links('vendor.pagination.default') }}
    </div>    
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.add-to-wishlist').forEach(function (wishlistButton) {
            wishlistButton.addEventListener('click', function (event) {
                event.preventDefault();
                fetch(this.closest('form').action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                }).then(response => response.json())
                .then(data => {
                    wishlistButton.style.display = 'none';
                    if (!data.error) {
                        alert('Product added to the wishlist.');
                    } else {
                        alert(data.error);
                    }
                }).catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.add-to-cart').forEach(function (cartButton) {
            cartButton.addEventListener('click', function (event) {
                event.preventDefault();

                const form = this.closest('form');
                
                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.error) {
                        alert(data.success);
                    } else {
                        alert(data.error);
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
