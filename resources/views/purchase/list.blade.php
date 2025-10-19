@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Purchase History</h1>
    @if($purchases->isEmpty())
        <p>You have not made any purchases yet.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchases as $purchase)
                    <tr>
                        <td>{{ $purchase->purchase_id }}</td>
                        <td>{{ $purchase->purchase_date->format('d/m/Y') }}</td>
                        @foreach($purchase->products as $product)
                            <td>${{ number_format($purchase->total*$product->pivot->quantity, 2) }}</td>
                            @break
                        @endforeach
                        <td>{{ $purchase->delivery_progress }}</td>
                        <td><a href="{{ route('purchase.show', $purchase->purchase_id) }}" class="btn btn-primary">View</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection