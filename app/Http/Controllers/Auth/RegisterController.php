<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Login;
use App\Models\Umkm;
use App\Models\ProfilToko;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function index(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectLoggedIn();
        }

        return view('auth.register', [
            'title_web' => 'Registrasi',
        ]);
    }

    public function proses(Request $request): RedirectResponse|View
    {
        if (Auth::check()) {
            return $this->redirectLoggedIn();
        }

        $validated = $request->validate([
            'nama_umkm'   => ['required', 'string', 'min:3', 'max:255'],
            'alamat_umkm' => ['required', 'string', 'min:5'],
            'nama'        => ['required', 'string', 'min:3', 'max:255'],
            'telepon'     => ['required', 'string', 'min:10', 'max:20'],
            'email'       => ['required', 'email', 'max:255', Rule::unique('login', 'email')],
            'pass'        => ['required', 'string', 'min:6'],
            'pass_confirm'=> ['required', 'same:pass'],
        ]);

        $umkm = null;
        DB::transaction(function () use ($validated, &$umkm) {
            // 1. Buat UMKM Baru
            $umkm = Umkm::create([
                'nama_umkm' => $validated['nama_umkm'],
                'alamat_umkm' => $validated['alamat_umkm'],
                'nama_pemilik' => $validated['nama'],
                'email' => $validated['email'],
                'telepon' => $validated['telepon'],
                'status' => 'aktif',
            ]);

            // 2. Buat Admin User (tanpa username, email sebagai username)
            $admin = Login::create([
                'nama_user'   => $validated['nama'],
                'user'        => $validated['email'],
                'pass'        => password_hash($validated['pass'], PASSWORD_DEFAULT),
                'email'       => $validated['email'],
                'email_login' => $validated['email'],
                'telepon'     => $validated['telepon'],
                'alamat'      => $validated['alamat_umkm'],
                'foto'        => '-',
                'level'       => 'Admin',
                'tgl_bergabung' => now()->format('Y-m-d'),
                'deleted_at'  => null,
                'umkm_id'     => $umkm->id,
            ]);

            // 3. Buat default ProfilToko
            ProfilToko::create([
                'nama_toko' => $validated['nama_umkm'],
                'alamat_toko' => $validated['alamat_umkm'],
                'telepon_toko' => $validated['telepon'],
                'email_toko' => $validated['email'],
                'pemilik_toko' => $validated['nama'],
                'website_toko' => '-',
                'tgl_update' => now(),
                'os' => 1,
                'print' => 1,
                'print_default' => 1,
                'driver' => 'Windows',
                'footer_struk' => 'Terima kasih atas kunjungan Anda',
                'pajak' => 0,
                'voucher' => 0,
                'diskon' => 0,
                'user_id' => $admin->id,
                'umkm_id' => $umkm->id,
            ]);
        });

        return redirect()
            ->route('login')
            ->with('success', '<strong>Registrasi berhasil!</strong> Akun UMKM <strong>' . e($umkm->nama_umkm) . '</strong> dengan kode <strong>' . e($umkm->kode_umkm) . '</strong> telah dibuat. Silakan login menggunakan email dan password Anda.');
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

