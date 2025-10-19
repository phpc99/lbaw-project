@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h1>Purchase Details</h1>
                </div>
                <div class="card-body">
                    <p class="card-text"><strong>Purchase ID:</strong> {{ $purchase->purchase_id }}</p>
                    <p class="card-text"><strong>Date:</strong> {{ $purchase->purchase_date }}</p>
                    <p class="card-text"><strong>Status:</strong> {{ $purchase->delivery_progress }}</p>
                    <p class="card-text"><strong>Address:</strong> {{ $purchase->purchaseAddress->street }}, {{ $purchase->purchaseAddress->city }}, {{ $purchase->purchaseAddress->country }} - {{ $purchase->purchaseAddress->postal_code }}</p>
                    <p class="card-text"><strong>Payment Method:</strong> {{ $purchase->purchasePaymentMethod->choice }}</p>
                    <hr>
                    <h2>Products:</h2>
                    <ul class="list-group">
                        @foreach($purchase->products as $product)
                            <li class="list-group-item">
                                <strong>{{ $product->name }}</strong> - ${{ $product->price }} x {{ $product->pivot->quantity }}
                            </li>
                        @endforeach
                    </ul>
                    <hr>
                    <p class="card-text mb-4" style="margin-bottom: 30px;"><strong>Total Amount:</strong> ${{ $purchase->total*$product->pivot->quantity }}</p>
                    
                    <a href="{{ Auth::user()->permissions === 'admin' ? route('admin.purchase.history') : route('purchase.list') }}" class="btn btn-primary mt-3 button" style="font-weight: bold; font-size: 1.2rem;">Back</a>
                    @if($purchase->delivery_progress != 'Delivered' && Auth::user()->permissions !== 'admin')
                        <form action="{{ route('purchase.delete', $purchase->purchase_id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: #d61818d0;">Cancel Order</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
