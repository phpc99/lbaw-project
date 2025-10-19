@extends('layouts.app')

@section('content')
    <div class="products-container">
        <div class="management-header">
            <h1>Product Management</h1>
            <div class="add-product-container" style="position: relative;">
                <!-- Add Product Button -->
                <button id="add-product-button">Add product</button>
            
                <!-- Modal Positioned Below -->
                <div id="add-product-modal">
                    <form action="{{ route('product.add') }}" method="POST">
                        @csrf
                        <label for="name">Name (Required):</label>
                        <input type="text" id="name" name="name" required placeholder="Name">
                        <label for="price">Price (Required):</label>
                        <input type="number" id="price" name="price" step="0.01" required placeholder="Number">
                        <label for="quantity">Quantity:</label>
                        <input type="number" id="quantity" name="quantity" step="1" placeholder="Quantity">
                        <label for="rating">Rating:</label>
                        <input type="number" id="rating" name="rating" step="0.1" min="0" max="5" placeholder="Rating">
                        <label for="footprint">Footprint:</label>
                        <input type="text" id="footprint" name="footprint" placeholder="Footprint">
                        <label for="description">Description (Required):</label>
                        <textarea id="description" name="description" required placeholder="Description"></textarea>
                        <button type="submit">Add</button>
                        <button type="button" id="close-modal" class="close-modal">Cancel</button>
                    </form>
                </div>
            </div>            
        </div>
        <div id="cards">
            @foreach ($products as $product)
                <article class="card">
                    <div style="display: flex; justify-content: space-between;">
                        <div class="product-tittle">
                            <h2>{{ $product->name }}</h2>
                        </div>
                    </div>
                    <!--<img src="{{$product->image}}" alt="{{$product->name}}" class="card-img-top">-->
        
                    <div class="products-details">
                        <p>Price: ${{ $product->price }}</p>
                        <p>{{ $product->description }}</p>
                    </div>
                    <div style="display: flex; gap: 20px;">
                        <a href="{{route('product.show', $product->product_id)}}" class="button details-btn">
                            View Details
                        </a>
                    </div>
                </article>
            @endforeach
        </div>
        <div class="pagination-container">
            {{ $products->links('vendor.pagination.default') }}
        </div>     
    </div>
@endsection