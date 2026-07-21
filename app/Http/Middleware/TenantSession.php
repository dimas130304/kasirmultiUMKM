<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

class TenantSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $segment = $request->segment(1);

        // Jika URL diawali dengan kode UMKM atau SUPERADMIN
        if ($segment && (str_starts_with(strtoupper($segment), 'UMKM') || strtoupper($segment) === 'SUPERADMIN')) {
            $umkmCode = strtoupper($segment);

            // Set default route parameter
            URL::defaults(['umkm_code' => $umkmCode]);

            // Set nama session cookie unik untuk tenant ini
            // Ini akan memastikan StartSession (yang berjalan setelahnya) menggunakan cookie ini
            Config::set('session.cookie', 'ksr_session_' . $umkmCode);
        }

        return $next($request);
    }
}
