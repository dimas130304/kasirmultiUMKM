<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($user->level !== 'Admin') {
            $umkm = $user->umkm;
            $umkmCode = $umkm ? $umkm->kode_umkm : null;
            if (!$umkmCode) {
                Auth::logout();
                return redirect()->route('login');
            }
            return redirect()->route('kasir.index', ['umkm_code' => $umkmCode]);
        }

        return $next($request);
    }
}
