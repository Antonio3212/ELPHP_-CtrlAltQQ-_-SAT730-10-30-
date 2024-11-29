<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    // GET method for fetching all products
    public function index()
    {
        // Fetch all products from the database
        $products = Product::all();

        // Prepend the full URL to the image path
        foreach ($products as $product) {
            if ($product->image_path) {
                $product->image_url = asset('storage/' . $product->image_path); // Generate full image URL
            }
        }

        // Return the products as JSON with the full image URL
        return response()->json([
            'products' => $products
        ], 200);
    }

    // POST method for creating a new product
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'category' => 'required|in:Vegetable Seeds,Flower Seeds,Herb Seeds,Fruit Seeds and Trees',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Image is optional
        ]);

        // If validation fails, return errors in response
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Handle file upload if there is an image
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        // Create a new product record in the database
        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'category' => $request->category,
            'image_path' => $imagePath,  // If image uploaded, save the path
        ]);

        // Return success response with the product details
        return response()->json([
            'message' => 'Product added successfully!',
            'product' => $product,
        ], 201);
    }
}
