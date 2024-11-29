<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Seller;
use App\Models\Buyer;
use Validator;

class AuthController extends Controller
{
    /**
     * Login user (either seller or buyer).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        // Check if the email belongs to a seller
        $seller = Seller::where('email', $request->email)->first();
        if ($seller && Hash::check($request->password, $seller->password)) {
            return response()->json([
                'status' => 'success',
                'message' => 'Login successful',
                'user_type' => 'seller',
                'data' => $seller,
            ], 200);
        }

        // Check if the email belongs to a buyer
        $buyer = Buyer::where('email', $request->email)->first();
        if ($buyer && Hash::check($request->password, $buyer->password)) {
            return response()->json([
                'status' => 'success',
                'message' => 'Login successful',
                'user_type' => 'buyer',
                'data' => $buyer,
            ], 200);
        }

        // If no match was found for either seller or buyer
        return response()->json([
            'status' => 'error',
            'message' => 'Invalid credentials',
        ], 401);
    }
}