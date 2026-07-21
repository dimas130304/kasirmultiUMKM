<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureSuperAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($user->level !== 'SuperAdmin') {
            $umkm = $user->umkm;
            $umkmCode = $umkm ? $umkm->kode_umkm : null;
            if (!$umkmCode) {
                Auth::logout();
                return redirect()->route('login');
            }
            if ($user->level === 'Admin') {
                return redirect()->route('home', ['umkm_code' => $umkmCode]);
            }
            return redirect()->route('kasir.index', ['umkm_code' => $umkmCode]);
        }

        return $next($request);
    }
}
