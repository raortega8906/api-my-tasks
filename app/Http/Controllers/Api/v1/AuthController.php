<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {

            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:5|confirmed',
            ]);

            $user = User::create($data);

            // $token = $user->createToken('auth-token')->plainTextToken;

            $data = [
                'message' => 'User registered successfully',
                'status' => 200,
                'data' => $user,
            ];

            return response()->json($data, 200);

        }
        catch (\Exception $e) {

            return response()->json([
                'message' => 'Failed to register user',
                'status' => 500,
                'error' => $e->getMessage()
            ], 500);

        }
    }

    public function login(Request $request)
    {
        try {

            $data = $request->validate([
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:5',
            ]);

            $token = auth()->attempt($data);

            if (!$token) {
                
                return response()->json([
                    'message' => 'Invalid credentials',
                    'status' => 401,
                ], 401);

            } 
            else{

                return response()->json([
                    'message' => 'User logged in successfully',
                    'status' => 200,
                    'data' => $data,
                    'token' => $token
                ], 200);

            }

        }
        catch (\Exception $e) {

            return response()->json([
                'message' => 'Failed to login user',
                'status' => 500,
                'error' => $e->getMessage()
            ], 500);
            
        }
    }

    public function logout(Request $request)
    {
        try {
            //
        }
        catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to logout user',
                'status' => 500,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function refreshToken(Request $request)
    {
        try {
            //
        }
        catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to refresh token',
                'status' => 500,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function profile(Request $request)
    {
        try {
            //
        }
        catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to get user',
                'status' => 500,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
