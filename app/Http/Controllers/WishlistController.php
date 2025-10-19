<?php

namespace App\Http\Controllers;

use App\Events\WishlistItemAdded;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Cache;

class WishlistController extends Controller
{
    public function show() {
        $wishlist = Wishlist::where('user_id', Auth::id())->with('product')->get();
    
        return view('wishlist.show', compact('wishlist'));
    }

    public function add($id) {

        $wish = Wishlist::where('product_id', $id)->where('user_id', Auth::id())->get();

        if ($wish->count() > 0) {
            return response()->json(['error' => 'Product already in wishlist'], 400);
        }

        $wish = [
            'product_id' => $id,
            'user_id' => Auth::id(),
        ];

        Wishlist::create($wish);
        
        event(new WishlistItemAdded(Auth::id()));

        return response()->json(['success' => 'Product added to wishlist']);
    }

    public function remove(Request $request, $id) {
        Wishlist::where('product_id', $id)->where('user_id', Auth::id())->delete();

        if ($request->ajax()) {
            return response()->json();
        }
    
        return redirect()->route('wishlist.show');
    }
}
