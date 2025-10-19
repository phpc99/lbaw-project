<?php

namespace App\Http\Controllers;

use App\Models\AdminNews;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function showProducts()
    {
        $products = Product::paginate(9);
        return view('admin.products', compact('products'));
    }

    public function showUsers()
    {
        $news = AdminNews::whereNull('category')->orderBy('created_at', 'desc')->get();
        return view('admin.users', compact('news'));
    }

    public function showOrders()
    {
        $news = AdminNews::where('category', 'order')->orderBy('created_at', 'desc')->get();
        return view('admin.orders', compact('news'));
    }

    public function destroy($id) {
        $user = User::findOrFail($id);

        if (Auth::id() === $user->user_id) {
            return redirect()->route('user.profile')->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('products.list')->with('success', 'User has been permanently banned.');
    }
}
