<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $roleSlug
     */
    public function handle(Request $request, Closure $next, string $roleSlug): Response
    {
        if (!auth()->check()) {
            return redirect('/admin/login');
        }

        $user = auth()->user();

        // Check using role_id belongsTo relation
        if ($user->role && $user->role->slug === $roleSlug) {
            return $next($request);
        }

        // Fallback: check using many-to-many roles relation
        if ($user->hasRole($roleSlug)) {
            return $next($request);
        }

        abort(403, 'Unauthorized access.');
    }
}

