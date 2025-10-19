@extends('layouts.app')

@section('content')
<div class="products-container">
    <div class="catalog-header">
        <h1>Product Catalog</h1>
        <img class="filter-image" src="{{ asset('/images/general_icons/filter.png') }}" alt="Filter Image" style="width: 40px; height: 40px; cursor: pointer;">
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const filterImage = document.querySelector(".filter-image");
            const filterBox = document.querySelector(".filter-box");

            filterImage.addEventListener("click", () => {
                if (filterBox.classList.contains("show")) {
                    filterBox.classList.remove("show");
                    setTimeout(() => filterBox.style.display = "none", 300); 
                } else {
                    filterBox.style.display = "block";
                    setTimeout(() => filterBox.classList.add("show"), 0);
                }
            });
        });
    </script>
    
    <!-- Filter Box -->
    <div class="filter-box-wrapper">
        <div class="filter-box" style="display: none;">
            <form id="filter-form" method="GET" action="{{ route('products.list') }}">
                <h3>Filters</h3>
                <!-- Price Range Filter -->
                <div class="filter-group">
                    <label for="price-range">Price Range:</label>
                    <select id="price-range" name="price_range">
                        <option value="">All Prices</option>
                        <option value="0-10">0 - 9.99</option>
                        <option value="10-25">10 - 24.99</option>
                        <option value="25-50">25 - 49.99</option>
                        <option value="50-100">50 - 99.99</option>
                        <option value="100-200">100 - 199.99</option>
                        <option value="200+">200+</option>
                    </select>
                </div>
    
                <!-- Category Filter -->
                <div class="filter-group">
                    <label for="category">Category:</label>
                    <select id="category" name="category">
                        <option value="">All Categories</option>
                        @foreach (\App\Models\Category::all() as $category)
                            <option value="{{ $category->category_id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
    
                <button type="submit" class="apply-filters">Apply Filters</button>
            </form>
        </div>
    </div>
    

    <!-- Product Cards -->
    <div id="cards">
        @foreach ($products as $product)
            <article class="card">
                <div class="card-header">
                    <img src="{{ asset($product->photo ?? 'images/general_icons/generic_product3.png') }}" 
                         alt="{{ $product->name}}" 
                         class="card-img-top"/>
                    @if(Auth::check() && !$product->wishlistUsers->contains(Auth::user()))
                        <form action="{{ route('wishlist.add', $product->product_id) }}" method="POST" class="wishlist-form">
                            @csrf
                            <button type="submit" class="add-to-wishlist">
                                <img src="{{ asset('images/general_icons/wishlist_icon.png') }}" 
                                     alt="Add to wishlist" 
                                     class="hover-effect-icon2">
                            </button>
                        </form>
                    @endif
                </div>
                
                <div class="product-title">
                    <h2>{{ $product->name }}</h2>
                </div>


                <div class="products-details">
                    <p>Price: ${{ $product->price }}</p>
                    <p>{{ $product->description }}</p>
                </div>
                <div style="display: flex; gap: 20px;">
                    <a href="{{route('product.show', $product->product_id)}}" class="button details-btn">View Details</a>
                    <form action="{{ route('cart.add', $product->product_id) }}" method="POST">
                        @csrf
                        <button class="add-to-cart" type="submit">Add to Cart</button>
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