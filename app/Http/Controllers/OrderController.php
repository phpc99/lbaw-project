<?php

namespace App\Http\Controllers;

use App\Events\OrderStatusUpdated;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Purchase;

class OrderController extends Controller {

    public function show() {
        // Get orders from DB
        $orders = Order::all();

        // Return all orders
        return view('orders.show', compact('orders'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'delivery_progress' => 'required|in:Pending,Shipped,Delivered',
        ]);

        // Get purchase information
        $purchase = Purchase::with('purchaseUser')->findOrFail($id);

        if (!$purchase->purchaseUser) {
            return back()->withErrors(['error' => 'User not associated with this purchase.']);
        }

        // Update order delivery progress
        $purchase->delivery_progress = $request->input('delivery_progress');
        event(new OrderStatusUpdated($purchase));
        $purchase->save();

        return redirect()->route('orders.show')->with('success', 'Purchase status updated successfully.');
    }
}
