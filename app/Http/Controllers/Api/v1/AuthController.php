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
            //
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
            //
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
