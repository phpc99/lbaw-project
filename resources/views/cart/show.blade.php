@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Your Shopping Cart</h1>

    @if ($cartItems->isNotEmpty())
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cartItems as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>
                            <div class="quantity-controls">
                                <!-- Minus Link -->
                                <a href="#" class="minus-link" data-product-id="{{ $item->product_id }}" data-action="decrease">
                                    <img src="{{ asset('images/general_icons/minus.png') }}" alt="Minus" width="20" height="20"/>
                                </a>
                                
                                <span class="quantity-number">{{ $item->quantity }}</span>
                                
                                <!-- Plus Link -->
                                <a href="#" class="plus-link" data-product-id="{{ $item->product_id }}" data-action="increase">
                                    <img src="{{ asset('images/general_icons/plus.png') }}" alt="Plus" width="20" height="20"/>
                                </a>
                            </div>
                            
                        </td>
                        <td class="product-price">${{ $item->product->price }}</td>
                        <td class="product-total">${{ $item->total }}</td>
                        <td>
                            <form action="{{ route('cart.remove', $item->product_id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger">Remove</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="cart-checkout-btn">
            <h3>Total: <span class="cart-total-display">${{ $total }}</span></h3>
            <a href="{{ route('cart.checkout') }}" class="button" style="width: 120px;">Checkout</a>
        </div>
    @else
        <p>Your cart is empty.</p>
    @endif
    <form action="{{ url()->previous() }}" method="get">
        <button type="submit" class="">Back</button>
    </form>
</div>

<script type="text/javascript" src="{{ asset('js/app.js') }}" defer></script>

@endsection
