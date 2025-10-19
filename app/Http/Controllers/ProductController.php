<?php

namespace App\Http\Controllers;

use App\Events\NewProductAddedEvent;
use App\Events\PriceChangedEvent;
use App\Models\Category;
use App\Models\Notification;
use App\Models\Product;
use App\Models\User;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ProductController extends Controller
{
    public function addReview(Request $request, $id)
    {
        $user = Auth::id(); // Usuário autenticado
        $product = Product::findOrFail($id); // Produto válido
        

        // Validação dos dados
        $validatedData = $request->validate([
            'score' => 'required|numeric|min:0|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Buscar review existente
        $existingReview = Review::where('product_id', $id)->where('user_id', $user)->first();

        if ($existingReview) {
            // Atualizar review existente
            $existingReview->update([
                'score' => $validatedData['score'],
                'comment' => $validatedData['comment'],
                'rev_date' => now(),
            ]);

            return redirect()->route('product.show', $id)
                ->with('success', 'Review updated successfully.');
        }
        // Criar nova review
        Review::create([
            'score' => $validatedData['score'],
            'rev_date' => now(),
            'comment' => $validatedData['comment'],
            'product_id' => $id,
            'user_id' => $user,
        ]);

        return redirect()->route('product.show', $id)
            ->with('success', 'Review added successfully.');
    }


    // Display the homepage
    /*public function index()
    {
        $products = Product::latest()->take(5)->get(); // Example: fetch latest products (5)
        return view('home', compact('products')); 
    }*/

    // List all products
    public function list(Request $request)
    {
        $query = Product::query();

        if ($request->has('price_range') && $request->price_range) {
            $priceRange = explode('-', $request->price_range);

            if (count($priceRange) == 2) {
                $query->whereBetween('price', [$priceRange[0], $priceRange[1]]);
            } elseif ($priceRange[0] == '200+') {
                $query->where('price', '>=', 200);
            }
        }

        if ($request->has('category') && $request->category) {
            $query->whereHas('categories', function ($query) use ($request) {
                $query->where('category.category_id', $request->category);
            });
        }

        $products = $query->with('wishlistUsers')->paginate(9);
        return view('products.list', compact('products')); 
    }

    // Show a specific product
    public function show($id){
        $product = Product::with('categories', 'reviews.user')->findOrFail($id);
        $all_categories = Category::all();

        // Verifica se o usuário autenticado já comprou o produto
        $user = Auth::user();
        $hasPurchased = false;

        if ($user) {
            $hasPurchased = $user->purchases()
                ->whereHas('products', function ($query) use ($id) {
                    $query->where('purchase_product.product_id', $id); // Use o alias correto
                })->exists();
        }

        return view('products.show', compact('product', 'all_categories', 'hasPurchased'));
    }

    public function add(Request $request) {

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string|max:5000',
            'quantity' => 'nullable|integer|min:0',
            'rating' => 'nullable|numeric|min:0|max:5',
            'footprint' => 'nullable|string|max:255',
        ]);

        $this->authorize('create', Product::class);

        $product = new Product();
        $product->name = $request->input('name');
        $product->price = $request->input('price');
        $product->quantity = $request->input('quantity') ?? 0;
        $product->rating = $request->input('rating') ?? 0;
        $product->foot_print = $request->input('footprint');
        $product->description = $request->input('description');
        $product->publication_date = now();
        $product->save();

        // notify users that a new product has been added
        event(new NewProductAddedEvent($product));

        return redirect()->route('admin.products')->with('success', 'Product added successfully!');
    }

    public function remove($id) {
        $product = Product::findOrFail($id);
        $this->authorize('delete', $product);
        $product->delete();

        return redirect()->route('admin.products')->with('success', 'Product removed successfully!.');
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'category_id' => 'required|exists:category,category_id',
        ]);

        $product = Product::findOrFail($id);
        $oldPrice = $product->price;

        $product->price = $validatedData['price'];
        $product->quantity = $validatedData['quantity'];
        $product->categories()->sync([$validatedData['category_id']]); 

        $this->authorize('update', $product);
        $product->save();

        // notify users that have that product in their cart
        if ($oldPrice != $product->price) {
            event(new PriceChangedEvent($product, $oldPrice));
        }

        return redirect()->route('product.show', $id)->with('success', 'Product updated successfully!');
    }

    public function index(){
        $products = Product::paginate(9); // 9 products per page
        return view('product.index', compact('products'));
    }

    public function checkProductName(Request $request)
    {
        $exists = Product::where('name', $request->name)->exists();
        return response()->json(['exists' => $exists]);
    }



    /*// API: List all products
    public function apiList()
    {
        return response()->json(Product::all());
    }

    // API: Show a specific product
    public function apiShow($id)
    {
        $product = Product::findOrFail($id);
        return response()->json($product);
    }*/

    /*public function search(Request $request) {

        $request->validate([
            'query' => 'required|string|max:255',
        ]);

        $searchTerm = $request->input('query');

        $products = Product::where('name', 'LIKE', "%{$searchTerm}%")->orWhere('description', 'LIKE', "%{$searchTerm}%")->get();

        return view('products.index', compact('products'));
    }*/

    
}
