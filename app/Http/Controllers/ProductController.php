<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;

class ProductController extends Controller
{
    // No authentication middleware applied here
    public function store(Request $request)
    {
        // Validate the incoming request
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
            // Store the image in the 'products' folder on the 'public' disk
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

        // Return a success response with product details
        return response()->json([
            'message' => 'Product added successfully!',
            'product' => $product,
        ], 201);
    }
}
