<?php

namespace App\Http\Middleware;

use App\Models\Login;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Karena sekarang kita menggunakan sesi per-tenant, jika ada user
                // yang terdeteksi login di sesi global (laravel_session), 
                // itu berarti ini adalah sesi usang sebelum update multi-tenant.
                // Kita harus menghapusnya agar tidak terjadi infinite redirect loop.
                Auth::logout();
                return redirect()->route('login');
            }
        }

        return $next($request);
    }
}
