<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Login;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function index(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectLoggedIn();
        }

        $umkms = \App\Models\Umkm::where('status', 'aktif')->orderBy('nama_umkm')->get(['nama_umkm', 'kode_umkm', 'nama_pemilik']);
        return view('auth.login', [
            'title_web' => 'Login',
            'umkms' => $umkms,
        ]);
    }

    public function proses(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user' => ['required', 'string', 'max:255'],
            'pass' => ['required', 'string'],
        ]);

        // Cari user berdasarkan username, email, atau email_login
        $user = Login::active()
            ->where(function($q) use ($validated) {
                $q->where('user', $validated['user'])
                  ->orWhere('email', $validated['user'])
                  ->orWhere('email_login', $validated['user']);
            })
            ->first();

        if (! $user) {
            return redirect()
                ->route('login')
                ->with('failed', '<strong>Login gagal.</strong> Akun tidak ditemukan.');
        }

        // Cek apakah UMKM aktif (kecuali SuperAdmin)
        $umkmCode = 'SUPERADMIN';
        if ($user->umkm_id) {
            $umkm = \App\Models\Umkm::find($user->umkm_id);
            if ($umkm && $umkm->status !== 'aktif') {
                return redirect()
                    ->route('login')
                    ->with('failed', '<strong>Login gagal.</strong> UMKM Anda dinonaktifkan oleh administrator.');
            }
            if ($umkm) {
                $umkmCode = $umkm->kode_umkm;
            }
        }

        if (! password_verify($validated['pass'], $user->pass)) {
            return redirect()
                ->route('login')
                ->with('failed', '<strong>Login gagal.</strong> Password salah.');
        }

        // Generate one-time login token untuk transisi ke tenant session
        $token = \Illuminate\Support\Str::random(60);
        
        \App\Models\LoginToken::create([
            'token' => $token,
            'user_id' => $user->id,
            'umkm_code' => $umkmCode,
            'expires_at' => now()->addMinutes(5),
        ]);

        return redirect()->route('auth-callback', ['umkm_code' => $umkmCode, 'token' => $token]);
    }

    public function authCallback(Request $request, $umkmCode)
    {
        $tokenStr = $request->query('token');
        if (!$tokenStr) {
            return redirect()->route('login')->with('failed', 'Token login tidak valid.');
        }

        $token = \App\Models\LoginToken::where('token', $tokenStr)
            ->where('umkm_code', $umkmCode)
            ->where('expires_at', '>', now())
            ->first();

        if (!$token) {
            return redirect()->route('login')->with('failed', 'Token login kedaluwarsa atau tidak valid. Silakan login kembali.');
        }

        $user = Login::find($token->user_id);
        if (!$user) {
            return redirect()->route('login')->with('failed', 'User tidak ditemukan.');
        }

        // Hapus token agar tidak bisa dipakai lagi (One-time use)
        $token->delete();

        Auth::login($user);
        $request->session()->regenerate();

        return $this->redirectLoggedIn()
            ->with('success', '<strong>Hai '.$user->nama_user.'!</strong> Selamat datang kembali.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing');
    }

    private function redirectLoggedIn(): RedirectResponse
    {
        $user = Auth::user();

        if ($user->level === 'SuperAdmin') {
            return redirect()->route('superadmin.index');
        }

        $umkm = $user->umkm;
        $umkmCode = $umkm ? $umkm->kode_umkm : null;

        if (!$umkmCode) {
            return redirect()->route('login');
        }

        if ($user->level === 'Admin') {
            return redirect()->route('home', ['umkm_code' => $umkmCode]);
        }

        return redirect()->route('kasir.index', ['umkm_code' => $umkmCode]);
    }
}
