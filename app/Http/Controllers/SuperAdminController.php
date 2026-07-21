<?php

namespace App\Http\Controllers;

use App\Models\Umkm;
use App\Models\Login;
use App\Models\ProfilToko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SuperAdminController extends Controller
{
    public function index(): View
    {
        // Load UMKM with count of users and transactions
        $umkmList = Umkm::withCount(['users', 'transaksi'])->get();
        
        // Detailed platform stats
        $stats = [
            'total_umkm' => $umkmList->count(),
            'aktif_umkm' => $umkmList->where('status', 'aktif')->count(),
            'total_users' => $umkmList->sum('users_count'),
            'total_transaksi' => $umkmList->sum('transaksi_count'),
            'total_revenue' => (int) \App\Models\Transaksi::withoutGlobalScope('umkm')->sum('grandtotal'),
            'transaksi_today' => (int) \App\Models\Transaksi::withoutGlobalScope('umkm')->whereDate('created_at', now()->toDateString())->count(),
            'new_this_month' => $umkmList->where('created_at', '>=', now()->startOfMonth())->count(),
        ];

        // Top 5 UMKM by Revenue (Optional insight)
        $topUmkm = Umkm::withoutGlobalScope('umkm')
            ->withSum('transaksi', 'grandtotal')
            ->orderByDesc('transaksi_sum_grandtotal')
            ->limit(5)
            ->get();

        return view('superadmin.index', [
            'title_web' => 'SuperAdmin - Manajemen UMKM',
            'umkmList' => $umkmList,
            'stats' => $stats,
            'topUmkm' => $topUmkm
        ]);
    }

    public function detail(int $id): View
    {
        $umkm = Umkm::with('users')->findOrFail($id);
        
        return view('superadmin.detail', [
            'title_web' => 'Detail UMKM ' . $umkm->nama_umkm,
            'umkm' => $umkm,
        ]);
    }

    public function toggleStatus(int $id)
    {
        $umkm = Umkm::findOrFail($id);
        $newStatus = $umkm->status === 'aktif' ? 'nonaktif' : 'aktif';
        
        $umkm->update(['status' => $newStatus]);

        return redirect()->route('superadmin.index')
            ->with('success', 'Status UMKM ' . $umkm->nama_umkm . ' berhasil diubah menjadi ' . $newStatus . '.');
    }

    public function create(): View
    {
        return view('superadmin.form', [
            'title_web' => 'Tambah UMKM Baru',
            'umkm' => null
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_umkm' => 'required|string|max:255',
            'alamat_umkm' => 'nullable|string',
            'nama_pemilik' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:umkm,email',
            'telepon' => 'nullable|string|max:50',
            'admin_pass' => 'required|string|min:6',
        ]);

        DB::transaction(function () use ($validated) {
            $umkm = Umkm::create([
                'nama_umkm' => $validated['nama_umkm'],
                'alamat_umkm' => $validated['alamat_umkm'],
                'nama_pemilik' => $validated['nama_pemilik'],
                'email' => $validated['email'],
                'telepon' => $validated['telepon'],
                'status' => 'aktif',
            ]);

            // Create admin user for this UMKM
            $admin = Login::create([
                'nama_user' => $validated['nama_pemilik'],
                'user' => $validated['email'],
                'pass' => password_hash($validated['admin_pass'], PASSWORD_DEFAULT),
                'email' => $validated['email'],
                'email_login' => $validated['email'],
                'telepon' => $validated['telepon'],
                'alamat' => $validated['alamat_umkm'],
                'foto' => '-',
                'level' => 'Admin',
                'tgl_bergabung' => now()->format('Y-m-d'),
                'umkm_id' => $umkm->id,
            ]);

            // Create default ProfilToko
            ProfilToko::create([
                'nama_toko' => $validated['nama_umkm'],
                'alamat_toko' => $validated['alamat_umkm'] ?? '-',
                'telepon_toko' => $validated['telepon'] ?? '-',
                'email_toko' => $validated['email'],
                'pemilik_toko' => $validated['nama_pemilik'],
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

        return redirect()->route('superadmin.index')
            ->with('success', 'UMKM baru dan akun Admin berhasil didaftarkan.');
    }

    public function edit(int $id): View
    {
        $umkm = Umkm::findOrFail($id);
        return view('superadmin.form', [
            'title_web' => 'Edit UMKM ' . $umkm->nama_umkm,
            'umkm' => $umkm,
        ]);
    }

    public function update(Request $request, int $id)
    {
        $umkm = Umkm::findOrFail($id);
        
        $validated = $request->validate([
            'nama_umkm' => 'required|string|max:255',
            'alamat_umkm' => 'nullable|string',
            'nama_pemilik' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:umkm,email,' . $id,
            'telepon' => 'nullable|string|max:50',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $umkm->update($validated);

        return redirect()->route('superadmin.index')
            ->with('success', 'Data UMKM berhasil diperbarui.');
    }
}
