<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    public function list() {
        $user = Auth::user();

        $purchases = $user->purchases->sortByDesc('purchase_id');

        return view('purchase.list', compact('purchases'));
    }

    public function show($id) {
        $purchase = Purchase::with('products')
            ->with('purchaseUser')
            ->with('purchaseAddress')
            ->with('purchasePaymentMethod')
            ->findOrFail($id);

        return view('purchase.show', compact('purchase'));
    }   

    public function destroy($id) {
        $purchase = Purchase::findOrFail($id);

        $purchase->delete();

        return redirect()->route('purchase.list');
    }

    public function adminList() {
        
        $purchases = Purchase::with('purchaseUser', 'products')->orderByDesc('purchase_id')->get();
    
        return view('purchase.history', compact('purchases'));
    }
}
