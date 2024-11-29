<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seller;
use Validator;

class SellerController extends Controller
{
    public function registerSeller(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:sellers,email',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'required|string|max:15',
            'shop_name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        // Create a new seller record
        $seller = Seller::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => $request->password, // Password is hashed in the model
            'phone' => $request->phone,
            'shop_name' => $request->shop_name,
            'address' => $request->address,
        ]);

        // Return a response to the client
        return response()->json([
            'status' => 'success',
            'message' => 'Seller registered successfully',
            'data' => $seller,
        ], 201);
    }
}