<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::guard('sanctum')->user();
        if(!$user->QM) {
            return \response()->json(['fail' => 'You are unauthorized to enter this page!!'],401);
        }
        return $next($request);
    }

}
