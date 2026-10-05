<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (!Auth::guard($guard)->check()) {
                continue;
            }

            $user = Auth::guard($guard)->user();

            if ($user->role === 'warga') {
                return redirect()->route('user.dashboard');
            }

            if ($user->role === 'mitra') {
                return redirect()->route('mitra.dashboard');
            }

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            return redirect('/');
        }

        return $next($request);
    }
}