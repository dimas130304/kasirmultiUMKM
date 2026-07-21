<?php

namespace App\Http\Controllers;

use App\Models\ProfilToko;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class InfoController extends Controller
{
    public function index(): View
    {
        return view('info.index', [
            'title_web' => 'Pengaturan Toko',
            'edit' => ProfilToko::first(),
        ]);
    }

    public function update(Request $request)
    {
        $profil = ProfilToko::first();
        if ($profil) {
            $profil->update($request->validate([
                'nama_toko'           => 'required|string',
                'alamat_toko'         => 'required|string',
                'telepon_toko'        => 'nullable|string',
                'email_toko'          => 'nullable|string',
                'pemilik_toko'        => 'nullable|string',
                'website_toko'        => 'nullable|string',
                'footer_struk'        => 'nullable|string',
                'nama_bank'           => 'nullable|string|max:100',
                'no_rekening'         => 'nullable|string|max:50',
                'atas_nama_rekening'  => 'nullable|string|max:100',
                'catatan_pembayaran'  => 'nullable|string',
                'pajak'               => 'nullable|integer',
                'voucher'             => 'nullable|integer',
                'diskon'              => 'nullable|integer',
            ]));
        }

        return redirect()->route('info.index')->with('success', 'Pengaturan toko disimpan.');
    }

    public function print(): View
    {
        return view('info.print', ['edit' => ProfilToko::first(), 'title_web' => 'Pengaturan Print']);
    }

    public function printQr(): View
    {
        return view('info.print_qr', [
            'edit' => ProfilToko::first(),
            'umkm' => Auth::user()->umkm,
            'title_web' => 'Cetak QR Code Toko',
        ]);
    }
}
