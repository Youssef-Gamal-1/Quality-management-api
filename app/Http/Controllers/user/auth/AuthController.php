<?php

namespace App\Http\Controllers\user\auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\admin\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|min:5|max:255',
            'password' => 'required|min:6|max:255'
        ]);
        $user = User::where('academic_email',$validated['email'])
            ->orwhere('email',$validated['email'])
            ->orWhere('username',$validated['email'])
            ->orWhere('phone',$validated['email'])
            ->first();

        if(!$user){
            return response()->json([
                'msg' => 'Invalid Credentials'
            ],422);
        }

        return response()->json([
            'user' => new UserResource($user),
            'token' => $user->createToken('api_token')->plainTextToken
        ],200);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|min:5|max:255',
            'email' => 'required|min:5|max:255|unique:users',
            'password' => 'required|min:8|max:255',
            'confirm-password' => 'required|min:8|max:255|same:password',
            'academic_email' => 'required|min:5|max:255|unique:users',
            'phone' => 'required|min:5|max:255|unique:users',
            'username' => 'required|min:5|max:255|unique:users',
        ]);

        $user = User::create($validated);
        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'user' => new UserResource($user),
            'token' => $token
        ]);
    }
}

