<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class BuyerController extends Controller
{
    /**
     * Register a new buyer.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function registerBuyer(Request $request)
    {
        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:buyers,email',
            'password' => 'required|string|min:6|confirmed',
            'mobile_no' => 'required|string|max:15',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 400);
        }

        // Create a new buyer in the database
        $buyer = Buyer::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),  // Hash the password
            'mobile_no' => $request->mobile_no,
        ]);

        // Return a successful response with the buyer's data
        return response()->json([
            'status' => 'success',
            'message' => 'Registration successful',
            'data' => $buyer,
        ]);
    }
}