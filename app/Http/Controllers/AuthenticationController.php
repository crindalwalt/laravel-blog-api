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


    public function login (Request $request){
        $validated = $request->validate([
            'email' => "required|email|exists:users",
            'password' => "required|string",
        ]);

        $user = User::where("email",$validated["email"])->first();

        $password_verify = Hash::check($validated['password'], $user->password);

        if(!$password_verify){
            return response()->json([
                "message"  => "password does not match",
            ]);
        }

        $token = $user->createToken("mobile-token")->plainTextToken;

        return response()->json([
            "message" => "Login ho gya",
            "user" => $user,
            "token" => $token,
        ]);
       $token = $user->create_token("mobile-token")->plainTextToken;
    }
}
