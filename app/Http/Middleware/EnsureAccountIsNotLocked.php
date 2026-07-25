<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAccountIsNotLocked
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->is_locked) {
            auth()->logout();

            abort(Response::HTTP_FORBIDDEN, 'Account is locked.');
        }

        return $next($request);
    }
}
