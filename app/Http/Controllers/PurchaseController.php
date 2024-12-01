<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    // Existing store method for placing an order
    public function store(Request $request)
    {
        // Validate incoming request
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'customer_name' => 'required|string|max:255',
            'shipping_address' => 'required|string|max:255',
            'payment_method' => 'required|string|max:255',
        ]);

        // Retrieve the product by ID
        $product = Product::find($validated['product_id']);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Create the order
        $order = Order::create([
            'product_id' => $validated['product_id'],
            'customer_name' => $validated['customer_name'],
            'shipping_address' => $validated['shipping_address'],
            'payment_method' => $validated['payment_method'],
            'total_price' => $product->price,  
        ]);

        // Return a response
        return response()->json([
            'message' => 'Order placed successfully',
            'order' => $order
        ], 201);
    }

    // New method to fetch all orders (purchases)
    public function index()
    {
        // Fetch all orders along with the related product information
        $orders = Order::with('product')->get();  // Assuming 'product' is a relationship in the Order model

        // Return the list of orders in a JSON response
        return response()->json($orders, 200);
    }
}
