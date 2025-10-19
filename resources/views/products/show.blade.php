@extends('layouts.app')

@section('content')
<div class="container">
    <div class="show-products-header">
        <h1>{{ $product->name }}</h1>
        <div class="show-product-buttons" style="display: flex; gap: 20px;">
            @if (Auth::user() && Auth::user()->isAdmin())
                <div class="edit-product-section">
                    <button id="toggle-edit-form" style="background: none; border: none">
                        <img src="{{ asset('images/general_icons/edit_icon2.png') }}" alt="Edit Product" class="hover-effect-icon" style="width: 38px; height: 38px;">
                    </button>
                    
                    <form id="edit-form" action="{{ route('product.update', $product->product_id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('PUT')
                        <div class="edit-form-group">
                            <label for="edit-price">Price</label>
                            <input type="number" id="edit-price" name="price" step="0.01" value="{{ $product->price }}" class="form-control">
                        </div>

                        <div class="edit-form-group">
                            <label for="edit-stock">Stock</label>
                            <input type="number" id="edit-stock" name="quantity" step="1" value="{{ $product->quantity }}" class="form-control">
                        </div>

                        <div class="edit-form-group">
                            <label for="edit-category">Category</label>
                            <select id="edit-category" name="category_id" class="form-control">
                                @foreach ($all_categories as $cat)
                                    <option value="{{ $cat->category_id }}" {{ $product->categories->contains('id', $cat->category_id) ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="edit-form-actions">
                            <button type="submit" class="btn btn-success">Save Changes</button>
                            <button type="button" id="cancel-edit" class="btn btn-secondary">Cancel</button>
                        </div>
                    </form>
                </div>
            @endif
            <!-- Add to Cart -->
            <div class="edit-action">
                @if (Auth::user() && !Auth::user()->isAdmin())
                    <form  action="{{ route('cart.add', $product->product_id) }}" method="POST">
                        @csrf
                        <button type="submit">Add to Cart</button>
                    </form>
                @endif
                <!-- Remove Product -->
                @if (Auth::user() && Auth::user()->isAdmin())
                    <form action="{{ route('product.remove', $product->product_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this product?');">
                        @csrf
                        @method('DELETE')
                        <button id="remove-button" type="submit" class="btn-danger" style="background: none; border: none">
                            <img src="{{ asset('images/general_icons/delete_icon2.png') }}" alt="Remove Product" class="hover-effect-icon2" style="width: 35px; height: 35px;">
                        </button>
                    </form>
            @endif
            </div>
        </div>
    </div>
        <div class="container-attr">
            <p>Price: {{$product->price}}</p>
            <p>Stock: {{$product->quantity}}</p>
            <p>Categories:</p>
            @php
                $p_categories = $product->categories;
            @endphp
            @foreach ($p_categories as $category)
                <p>
                    <a href="{{ auth()->check() && auth()->user()->isAdmin() ? route('categories.list') : route('categories.show', $category->category_id) }}" class="category-link">
                        {{ $category->name ?? 'Uncategorized' }}
                    </a>
                </p>
            @endforeach
        </div>

    <!-- Comments Section -->
    <div class="comments-section">
        <h2>Reviews</h2>
        @foreach ($product->reviews as $review)
            <div class="review">
                @if ($review->user)
                    <strong>{{ $review->user->name }}</strong> rated it: 
                    <span>{{ $review->score }}/5</span>
                    @if ($review->comment)
                        <p>{{ $review->comment }}</p>
                    @endif
                    <p><small>Reviewed on {{ $review->rev_date }}</small></p>
                @else
                    <strong>Unknown User</strong> rated it:
                @endif
            </div>
        @endforeach

        @auth
            @if ($hasPurchased)
                @php
                    $userReview = $product->reviews->firstWhere('user_id', Auth::id());
                @endphp

                @if ($userReview)
                    <!-- Formulário para editar review -->
                    <form action="{{ route('product.addReview', $product->product_id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="score">Update your rating (0-5):</label>
                            <input type="number" id="score" name="score" min="0" max="5" class="form-control" required value="{{ $userReview->score }}">
                        </div>
                        <div class="form-group">
                            <label for="comment">Update your comment:</label>
                            <textarea id="comment" name="comment" class="form-control">{{ $userReview->comment }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Review</button>
                    </form>
                @else
                    <!-- Formulário para adicionar uma nova review -->
                    <form action="{{ route('product.addReview', $product->product_id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="score">Rating (0-5):</label>
                            <input type="number" id="score" name="score" min="0" max="5" class="form-control" required placeholder="Rate the product">
                        </div>
                        <div class="form-group">
                            <label for="comment">Comment (optional):</label>
                            <textarea id="comment" name="comment" class="form-control" placeholder="Leave a review"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Review</button>
                    </form>
                @endif
            @else
                <p>You need to purchase this product before leaving a review.</p>
            @endif
        @else
            <p><a href="{{ route('login') }}">Log in</a> to leave a review.</p>
        @endauth
    </div>
    <form action="{{ url()->previous() }}" method="get">
        <button type="submit" class="">Back</button>
    </form>
    
</div>

<script type="text/javascript" src="{{ url('public/js/app.js') }}" defer></script>
@endsection
