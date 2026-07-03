<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = auth()->user();

        if (! $user) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            abort(403);
        }

        // Administrator melewati semua pengecekan permission
        if ($user->hasRole('administrator')) {
            return $next($request);
        }

        if (! $user->hasPermission($permission)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Akses ditolak.'], 403);
            }
            abort(403, 'Anda tidak memiliki izin untuk mengakses fitur ini.');
        }

        return $next($request);
    }
}
