@extends('layouts.app')

@section('content')
<div class="container">
    @if (Auth::check() && Auth::user()->permissions === 'admin')
        <h1>All Purchase History</h1>

        @if ($purchases->isNotEmpty())
            <table class="table purchases-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>User</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchases as $purchase)
                        <tr>
                            <td>{{ $purchase->purchase_id }}</td>
                            <td>{{ $purchase->purchaseUser->name ?? 'Unknown' }}</td>
                            <td>{{ $purchase->purchase_date->format('Y-m-d') }}</td>
                            <td>${{ number_format($purchase->total, 2) }}</td>
                            <td>{{ $purchase->delivery_progress }}</td>
                            <td>
                                <a href="{{ route('purchase.show', $purchase->purchase_id) }}" class="btn btn-primary">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No purchases found.</p>
        @endif
    @else
        <p>You do not have permission to access this page.</p>
    @endif
</div>
@endsection
