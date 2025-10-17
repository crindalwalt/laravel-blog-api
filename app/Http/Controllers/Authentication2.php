<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class Authentication2 extends Controller
{
    public function registerv2(Request $request){

        $validated = $request->validate([
            "name"=>"required|string",
            "email"=>"required|email|unique:users",
            "password"=>"required|string|confirmed",
        ]);

         $user = User::create([
            "name"=>$validated['name'],
            "email"=>$validated['email'],
            "password"=>Hash::make($validated['password']),
         ]);

         $token = $user->createToken("mobile-app-v2")->plainTextToken;
            return response()->json([
                "message"=>"User registered Successfully",
                "name"=>$validated['name'],
                "token"=>$token,
            ],200);
    }
       
    public function loginv2(Request $request){
        $validated = $request->validate([
            "name" =>"required|string",
            "password" =>"required|string",
        ]);

        $user = User::where("email",$validated['email'])->first();
        $password_verify = Hash::check($validated['password'],$user->password);
        if(!$password_verify){
            return response()->json([
                "message"=>"Password does not match",
            ]);
            $token = $user->create_token("mobile-token-v2")->plainTextToken;
            return response()->json([
                "message"=>"Login ho gya v2",
                "user"=>$user,
                "token"=>$token,
            ]);
        }

    }


}

