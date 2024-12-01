<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();


        foreach ($products as $product) {
            if ($product->image_path) {
                $product->image_url = asset('storage/' . $product->image_path); 
            }
        }

        return response()->json([
            'products' => $products
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'category' => 'required|in:Vegetable Seeds,Flower Seeds,Herb Seeds,Fruit Seeds and Trees',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'category' => $request->category,
            'image_path' => $imagePath,
        ]);

        return response()->json([
            'message' => 'Product added successfully!',
            'product' => $product,
        ], 201);
    }

    public function update(Request $request, $id)
{
    // Fetch the product by ID
    $product = Product::find($id);

    if (!$product) {
        return response()->json(['message' => 'Product not found'], 404);
    }

    // Validate the input (only the fields that are sent in the request are validated)
    $validator = Validator::make($request->all(), [
        'name' => 'nullable|string|max:255',
        'price' => 'nullable|numeric',
        'quantity' => 'nullable|integer',
        'category' => 'nullable|in:Vegetable Seeds,Flower Seeds,Herb Seeds,Fruit Seeds and Trees',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 400);
    }

    if ($request->has('name')) {
        $product->name = $request->name;
    }
    if ($request->has('price')) {
        $product->price = $request->price;
    }
    if ($request->has('quantity')) {
        $product->quantity = $request->quantity;
    }
    if ($request->has('category')) {
        $product->category = $request->category;
    }

    if ($request->hasFile('image')) {
        if ($product->image_path) {
            \Storage::delete('public/' . $product->image_path); 
        }

        $imagePath = $request->file('image')->store('products', 'public');
        $product->image_path = $imagePath;
    }

    $product->save();


    return response()->json([
        'message' => 'Product updated successfully!',
        'product' => $product, 
    ], 200);
}


  
    public function destroy($id)
    {

        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Delete the image if exists
        if ($product->image_path) {
            \Storage::delete('public/' . $product->image_path);
        }

        // Delete the product from the database
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully'], 200);
    }
}
