<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller {

    public function search(Request $request) {

        $query = $request->input('query');
        $type = $request->input('type', 'products');

        if ($type === 'users') {
            $results = User::where('name', 'LIKE', "%{$query}%")->orWhere('email', 'LIKE', "%{$query}%")->get();

            return view('search.results', [
                'results' => $results,
                'type' => 'users',
                'query' => $query,
            ]);
        }
        // default: products search
        //$results = Product::where('name', 'LIKE', "%{$query}%")->orWhere('description', 'LIKE', "%{$query}%")->get();
        $results = Product::searchProducts($query);

        return view('search.results', [
            'results' => $results,
            'type' => 'products',
            'query' => $query,
        ]);
    }
}