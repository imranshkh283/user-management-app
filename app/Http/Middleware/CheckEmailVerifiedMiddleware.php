<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckEmailVerifiedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $email = $request->email;
        $isEmailVerified = User::where('email', $email)->first()->is_email_verified;
        if (!$isEmailVerified) {
            return response()->json(['message' => 'Email not verified'], 401);
        }
        return $next($request);
    }
}
