<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Product;

class CategoryController extends Controller
{
    // List all categories
    public function list()
    {
        $categories = Category::all();
        return view('categories.list', compact('categories')); 
    }

    public function makeNew(Request $request) {

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:category,name',
        ]);

        $category = new Category();
        $category->name = $validated['name'];
        unset($category->category_id);
        $category->save();

        return redirect()->route('categories.list')->with('success', 'Category added successfully!');
    }
    
    public function edit(Request $request, $id) {

        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $category = Category::findOrFail($id);
        $category->update(['name' => $request->name]);

        return redirect()->route('categories.list')->with('success', 'Category updated successfully!');
    }

    public function erase($id) {

        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('categories.list')->with('success', 'Category deleted successfully!');
    }

    public function show($id){
        $category = Category::findOrFail($id);
        $products = Product::whereHas('categories', function ($query) use ($id) {
            $query->where('product_category.category_id', $id);
        })->paginate(9);

        return view('categories.show', compact('category', 'products'));
    }

    
    /*// Pass categories to the products/show.blade.php view
    public function show()
    {
        $categories2 = Category::all();
        return view('products.show', compact('categories2'));
    }*/
}
