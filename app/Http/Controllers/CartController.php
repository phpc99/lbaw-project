<?php

namespace App\Http\Controllers;

use App\Events\CartItemAdded;
use App\Events\OrderPlaced;
use App\Events\PurchaseCompleted;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Models\Address;
use App\Models\Purchase;
use App\Models\PaymentMethod;
use App\Models\PaymentMethodCard;
use App\Models\PaymentMethodPayPal;
use App\Models\PaymentMethodMBWay;
use Illuminate\Support\Facades\Cache;

class CartController extends Controller
{
    public function show() {

        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
        //$total = $cartItems->sum('total');
        $cartItems->each(function ($item) {
            $item->total = $item->product->price * $item->quantity;
        });
        $total = $cartItems->reduce(function ($carry, $item) {
            return $carry + $item->total;
        }, 0);

        return view('cart.show', compact('cartItems', 'total'));
    }

    public function add(Request $request, $product_id) 
    {
        $item = Product::findOrFail($product_id);
        $user_id = Auth::id();

        $cartItem = Cart::where('user_id', $user_id)->where('product_id', $product_id)->first();
        
        if ($cartItem) {
            Cart::where('user_id', $user_id)
                       ->where('product_id', $product_id)
                       ->update([
                           'quantity' => $cartItem->quantity + 1,
                           'total' => $cartItem->total + $item->price,
                       ]);
        } else {
            Cart::create([
                'user_id' => $user_id,
                'product_id' => $product_id,
                'quantity' => 1,
                'total' => $item->price,
            ]);
        }

        if ($request->ajax()) {
            return response()->json(['success' => 'Product added to cart!']);
        }

        event(new CartItemAdded(Auth::id()));

        return redirect()->route('cart.show')->with('success', 'Product added to cart!');
    }

    public function update(Request $request, $product_id)
    {
        $cartItem = Cart::where('product_id', $product_id)->where('user_id', auth()->id())->firstOrFail();
        
        if (!$cartItem) {
            return response()->json(['success' => false], 404);
        }

        // Update the quantity
        $stock = Product::select('quantity')->find($product_id)->quantity;
        if ($request->quantity > $stock) {
            return response()->json([
                'success' => false,
                'message' => "Only {$stock} units are available for this product.",
                'currentQuantity' => $cartItem->quantity, // Return current valid quantity
            ]);
        }

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        event(new CartItemAdded(Auth::id()));
        
        return response()->json(['success' => true]);
    }
    
    public function remove($product_id) {

        $user_id = Auth::id();
        Cart::where('user_id', $user_id)->where('product_id', $product_id)->delete();

        return redirect()->route('cart.show')->with('success', 'Item removed from cart!');  
    }
    
    public function checkout() {

        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
        $total = $cartItems->sum('total');
        $addresses = Address::where('user_id', Auth::id())->get();

        return view('cart.checkout', compact('cartItems', 'total', 'addresses'));
    }

    public function checkoutProcess(Request $request) {

        $validated = $request->validate([
            'address_method' => 'required|string',
            'street' => 'nullable|string|max:255|required_if:address_method,0',
            'city' => 'nullable|string|max:25|required_if:address_method,0',
            'postal_code' => 'nullable|string|max:10|required_if:address_method,0',
            'country' => 'nullable|string|required_if:address_method,0',
            'payment_method' => 'required|string|in:Card,PayPal,MBWay',
            'card_number' => 'nullable|string|required_if:payment_method,Card',
            'expiry_date' => 'nullable|date|after:today|required_if:payment_method,Card',
            'name' => 'nullable|string|required_if:payment_method,Card',
            'email' => 'nullable|string|email|required_if:payment_method,PayPal',
            'mbway_phone' => 'nullable|string|required_if:payment_method,MBWay',
            'total' => 'required|numeric',
        ]);

        if ($validated['address_method'] == '0') {
            $address = Address::create([
                'street' => $validated['street'],
                'city' => $validated['city'],
                'postal_code' => $validated['postal_code'],
                'country' => $validated['country'],
                'user_id' => Auth::id(),
            ]);
        } else {
            $address = Address::findOrFail($validated['address_method']);
        }
        
        switch ($validated['payment_method']) {
            case 'Card':
                $payment = PaymentMethodCard::where('card_number', $validated['card_number'])->first();

                if ($payment) {
                    $payment->update([
                        'card_holder_name' => $validated['name'],
                        'expiry_date' => $validated['expiry_date'],
                    ]);
                } else {
                    $paymentMethod = PaymentMethod::Create([
                        'choice' => $validated['payment_method'],
                    ]);

                    $payment = PaymentMethodCard::create([
                        'payment_method_id' => $paymentMethod->payment_method_id,
                        'card_number' => $validated['card_number'],
                        'card_holder_name' => $validated['name'],
                        'expiry_date' => $validated['expiry_date'],
                    ]);
                }

                break;
    
            case 'PayPal':
                $payment = PaymentMethodPayPal::where('email', $validated['email'])->first();
                
                if (!$payment) {
                    $paymentMethod = PaymentMethod::Create([
                        'choice' => $validated['payment_method'],
                    ]);

                    $payment = PaymentMethodPayPal::create([
                        'payment_method_id' => $paymentMethod->payment_method_id,
                        'email' => $validated['email'],
                    ]);    
                }
                break;
    
            case 'MBWay':

                $payment = PaymentMethodMBWay::where('phone_number', $validated['mbway_phone'])->first();

                if (!$payment) {
                    $paymentMethod = PaymentMethod::Create([
                        'choice' => $validated['payment_method'],
                    ]);

                    $payment = PaymentMethodMBWay::create([
                        'payment_method_id' => $paymentMethod->payment_method_id,
                        'phone_number' => $validated['mbway_phone'],
                    ]);
                }

                break;
        }

        $purchase = Purchase::create([
            'user_id' => Auth::id(),
            'address_id' => $address->address_id,
            'payment_method_id' => $payment->payment_method_id,
            'purchase_date' => now(),
            'delivery_progress' => 'Pending',
            'total' => $validated['total']
        ]);

        $cartItems = json_decode($request->cartItems);
        
        foreach ($cartItems as $item) {
            $purchase->products()->attach($item->product_id, ['quantity' => $item->quantity]);
        }

        Cart::where('user_id', Auth::id())->delete();

        event(new PurchaseCompleted($purchase));
    
        return redirect()->route('purchase.show', $purchase->purchase_id)->with('success', 'Order placed successfully!');
    }

    // may need later
    /* public function update(Request $request, $product_id) {

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);
    
        // Update the cart item directly using a query
        $updated = Cart::where('user_id', Auth::id())
                       ->where('product_id', $product_id)
                       ->update([
                           'quantity' => $validated['quantity'],
                           'total' => $validated['quantity'] * Product::find($product_id)->price,
                       ]);
    
        if (!$updated) {
            return redirect()->route('cart.show')->withErrors('Failed to update cart item.');
        }
    
        return redirect()->route('cart.show')->with('success', 'Cart updated successfully!');
    }*/
}
