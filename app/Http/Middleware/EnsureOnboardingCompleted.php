<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOnboardingCompleted
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || $user->role !== 'warga') {
            return $next($request);
        }

        if ($request->routeIs('user.dashboard') || $request->routeIs('logout')) {
            return $next($request);
        }

        if (!$user->onboarding_completed || !$user->waste_bank_id) {

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Onboarding belum selesai.',
                    'action' => 'select_waste_bank',
                    'redirect' => route('user.dashboard'),
                ], 403);
            }

            return redirect()->route('user.dashboard');
        }

        return $next($request);
    }
}