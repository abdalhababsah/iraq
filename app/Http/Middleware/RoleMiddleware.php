<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check() || auth()->user()->role !== $role) {
            abort(403, 'غير مخول للوصول');
        }

        if (!auth()->user()->is_active) {
            auth()->logout();
            return redirect('/login')->withErrors(['account' => 'تم إلغاء تفعيل حسابك']);
        }

        return $next($request);
    }
}
