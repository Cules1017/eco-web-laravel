<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckGuest
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            if ($request->ajax()) {
                return response()->json([
                    'message' => __('messages.please_login'),
                    'redirect' => route('login')
                ], 401);
            }
            
            return redirect()->route('login')->with('message', __('messages.please_login'));
        }

        return $next($request);
    }
} 