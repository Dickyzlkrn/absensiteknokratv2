<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Arahkan sesuai jenis guard
                switch ($guard) {
                    case 'mahasiswa':
                        return redirect('/mahasiswa/dashboard');
                    case 'web':
                    case 'user':
                        return redirect('/dashboard/admin');
                    default:
                        return redirect('/login');
                }
            }
        }

        return $next($request);
    }
}
