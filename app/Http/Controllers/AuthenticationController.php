<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
{
    public function register(Request $request)
    {
        // validate the request
        $validated = $request->validate([
            'name' => "required",
            "email" => "required|unique:users|email",
            "password" => "required|confirmed|string"
        ]);

        $password_hash = Hash::make($validated['password']);

        $user = User::create($validated);

        $token = $user->createToken("mobile-app")->plainTextToken;

        return response()->json([
            "message" => "user registered successfully",
            "name" => $validated['name'],
            "token" => $token,
        ], 200);
    }
}
