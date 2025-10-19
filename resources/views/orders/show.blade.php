@extends('layouts.app')

@section('content')
<div class="container">
    @if (Auth::check() && Auth::user()->permissions === 'admin')
        <h1>Order Details</h1>

        @if ($orders->isNotEmpty())
            <table class="table orders-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>User</th>
                        <th>Purchase Date</th>
                        <th>Total</th>
                        <th>Products</th>
                        <th class="status-column">Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>{{ $order->purchase_id }}</td>
                            <td>{{ $order->user->name ?? 'Unknown' }}</td>
                            <td>{{ $order->purchase_date->format('Y-m-d') }}</td>
                            <td>${{ number_format($order->total, 2) }}</td>
                            <td>
                                <ul>
                                    @foreach ($order->products as $product)
                                        <li>{{ $product->name }} (x{{ $product->pivot->quantity }})</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="status-column">
                                <span class="status-display" id="status-text-{{ $order->purchase_id }}">
                                    {{ $order->delivery_progress }}
                                </span>
                                <form id="status-form-{{ $order->purchase_id }}" 
                                    action="{{ route('orders.edit', $order->purchase_id) }}" 
                                    method="POST" 
                                    style="display: none;">
                                    @csrf
                                    @method('PUT')
                                    <select name="delivery_progress">
                                        <option value="Pending" {{ $order->delivery_progress === 'Pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="Shipped" {{ $order->delivery_progress === 'Shipped' ? 'selected' : '' }}>Shipped</option>
                                        <option value="Delivered" {{ $order->delivery_progress === 'Delivered' ? 'selected' : '' }}>Delivered</option>
                                    </select>
                                </form>
                            </td>
                            <td>
                                <button class="btn-warning" onclick="editOrder({{ $order->purchase_id }})">Edit Order</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No orders found.</p>
        @endif
    @else
        <p>You do not have permission to access this page.</p>
    @endif
</div>
@endsection
