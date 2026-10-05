<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOnboardingCompleted
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user 
            && $user->role === 'warga' 
            && !$user->onboarding_completed
            && !$request->routeIs('warga.onboarding.*')
            && !$request->routeIs('logout')
        ) {
            return redirect()->route('warga.onboarding.index');
        }

        return $next($request);
    }
}