<?php

namespace App\Http\Controllers\admin\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreRequest;
use App\Models\User;
use Cassandra\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request): \Illuminate\Http\JsonResponse
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
        if($user->QM !== 1 || !Hash::check($request->password,$user->password)){
            return response()->json([
                'msg' => 'Invalid Credentials'
            ],422);
        }

        return response()->json([
            'data' => $user,
            'token' => $user->createToken('api_token')->plainTextToken
        ],200);

    }
}
