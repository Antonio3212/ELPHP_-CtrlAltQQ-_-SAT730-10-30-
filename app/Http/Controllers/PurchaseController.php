<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function store(Request $request)
    {

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'customer_name' => 'required|string|max:255',
            'shipping_address' => 'required|string|max:255',
            'payment_method' => 'required|string|max:255',
        ]);

        $product = Product::find($validated['product_id']);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $order = Order::create([
            'product_id' => $validated['product_id'],
            'customer_name' => $validated['customer_name'],
            'shipping_address' => $validated['shipping_address'],
            'payment_method' => $validated['payment_method'],
            'total_price' => $product->price,  
        ]);

        return response()->json([
            'message' => 'Order placed successfully',
            'order' => $order
        ], 201);
    }

    public function index()
    {
        $orders = Order::with('product')->get();  

        return response()->json($orders, 200);
    }
}
