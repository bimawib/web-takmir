<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){
        $request->validate([
            'name'=>'required|string|max:255',
            'email'=>'required|email:dns|max:255|unique:users',
            'password'=>'required|string|min:5|confirmed'
        ]);

        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>bcrypt($request->password)
        ]);

        $token = $user->createToken('webtakmir')->plainTextToken;

        return response()->json([
            'data'=>$user,
            'access_token'=>$token,
            'token_type'=>'Bearer'
        ]);
    }

    public function login(Request $request){
        $fields = $request->validate([
            'email'=>'required|email:dns',
            'password'=>'required|string'
        ]);

        // Check Email
        $user = User::where('email',$fields['email'])->first();

        // Check Password
        if(!$user || !Hash::check($fields['password'], $user->password)){
            return response()->json([
                'message'=>'Failed to Login'
            ], 401);
        }

        $token = $user->createToken('webtakmir')->plainTextToken;

        return response()->json([
            'data'=>$user,
            'access_token'=>$token,
            'token_type'=>'Bearer'
        ]);
    }

    public function logout(Request $request){
        auth()->user()->tokens()->delete();

        return response()->json([
            'message'=>'Succesfully logged out!'
        ]);
    }

}
