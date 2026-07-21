@extends('layouts.app')
@section('content')
<x-page-card title="Pengaturan Toko UMKM" icon="fa-cog">

    @if(session('success'))
    <div class="mb-6 flex items-center gap-3 rounded-2xl bg-emerald-50 border border-emerald-200 px-6 py-4 text-emerald-700 text-sm font-semibold">
        <div class="h-10 w-10 rounded-full bg-emerald-100 flex items-center justify-center">
            <i class="fa fa-check-circle text-emerald-500 text-xl"></i>
        </div>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Kolom Kiri & Tengah: Form Settings --}}
        <div class="lg:col-span-2">
            <form method="POST" action="{{ route('info.update') }}" class="space-y-6">
                @csrf

                {{-- ======= SECTION 1: INFO TOKO ======= --}}
                <div class="rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
                    <div class="bg-gradient-to-r from-teal-600 via-teal-500 to-emerald-500 px-7 py-5 flex items-center gap-3">
                        <div class="h-12 w-12 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center">
                            <i class="fa fa-store text-white text-xl"></i>
                        </div>
                        <span class="text-white font-bold text-base">Informasi Toko</span>
                    </div>
                    <div class="p-7 grid grid-cols-1 md:grid-cols-2 gap-6 bg-white">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-widest mb-2">Nama Toko <span class="text-red-500">*</span></label>
                            <input name="nama_toko" value="{{ $edit->nama_toko ?? '' }}" required
                                class="w-full rounded-2xl border-2 border-slate-200 px-5 py-3.5 text-base outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 transition-all duration-200 placeholder:text-slate-400"
                                placeholder="Masukkan nama toko Anda">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-widest mb-2">Alamat Toko <span class="text-red-500">*</span></label>
                            <textarea name="alamat_toko" required rows="3"
                                class="w-full rounded-2xl border-2 border-slate-200 px-5 py-3.5 text-base outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 transition-all duration-200 resize-none placeholder:text-slate-400"
                                placeholder="Masukkan alamat lengkap toko">{{ $edit->alamat_toko ?? '' }}</textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-widest mb-2">
                                <i class="fa fa-whatsapp text-green-500 mr-2"></i>No. WA / Telepon Admin
                            </label>
                            <input name="telepon_toko" value="{{ $edit->telepon_toko ?? '' }}"
                                placeholder="Contoh: 0812-3456-7890"
                                class="w-full rounded-2xl border-2 border-slate-200 px-5 py-3.5 text-base outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 transition-all duration-200 placeholder:text-slate-400">
                            <p class="text-[11px] text-slate-400 mt-2 font-medium">Nomor ini dipakai untuk pesan otomatis ke pelanggan</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-widest mb-2">
                                <i class="fa fa-envelope text-slate-400 mr-2"></i>Email Toko
                            </label>
                            <input name="email_toko" value="{{ $edit->email_toko ?? '' }}"
                                placeholder="toko@email.com"
                                class="w-full rounded-2xl border-2 border-slate-200 px-5 py-3.5 text-base outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 transition-all duration-200 placeholder:text-slate-400">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-widest mb-2">
                                <i class="fa fa-user text-slate-400 mr-2"></i>Nama Pemilik
                            </label>
                            <input name="pemilik_toko" value="{{ $edit->pemilik_toko ?? '' }}"
                                placeholder="Nama lengkap pemilik"
                                class="w-full rounded-2xl border-2 border-slate-200 px-5 py-3.5 text-base outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 transition-all duration-200 placeholder:text-slate-400">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-widest mb-2">
                                <i class="fa fa-file-text text-slate-400 mr-2"></i>Footer Struk
                            </label>
                            <input name="footer_struk" value="{{ $edit->footer_struk ?? '' }}"
                                placeholder="Contoh: Terima kasih sudah berkunjung!"
                                class="w-full rounded-2xl border-2 border-slate-200 px-5 py-3.5 text-base outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 transition-all duration-200 placeholder:text-slate-400">
                        </div>
                    </div>
                </div>

                {{-- ======= SECTION 2: INFO PEMBAYARAN ======= --}}
                <div class="rounded-3xl border border-teal-200 overflow-hidden shadow-sm">
                    <div class="bg-gradient-to-r from-teal-600 via-teal-500 to-emerald-500 px-7 py-5 flex items-center gap-3">
                        <div class="h-12 w-12 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center">
                            <i class="fa fa-credit-card text-white text-xl"></i>
                        </div>
                        <span class="text-white font-bold text-base">Informasi Pembayaran</span>
                    </div>
                    <div class="p-7 bg-white">
                        {{-- Preview Card --}}
                        <div class="mb-7 rounded-2xl border border-teal-100 bg-gradient-to-br from-teal-50 to-emerald-50 p-5">
                            <p class="text-xs font-bold text-teal-700 uppercase tracking-widest mb-3 flex items-center gap-2">
                                <div class="h-7 w-7 rounded-lg bg-teal-100 flex items-center justify-center">
                                    <i class="fa fa-eye text-teal-600"></i>
                                </div>
                                Preview pesan WA ke pelanggan
                            </p>
                            <div class="font-mono text-xs text-slate-700 bg-white rounded-2xl border border-slate-200 p-4 leading-relaxed whitespace-pre-line shadow-inner">💳 *INFORMASI PEMBAYARAN*
Bank    : {{ $edit->nama_bank ?? '[Nama Bank]' }}
No. Rek : {{ $edit->no_rekening ?? '[No. Rekening]' }}
A.n     : {{ $edit->atas_nama_rekening ?? '[Atas Nama]' }}
{{ $edit->catatan_pembayaran ? "\n📝 " . $edit->catatan_pembayaran : '' }}

_Silakan transfer & kirim bukti ke sini ya_ 🙏</div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-widest mb-2">
                                    <i class="fa fa-university mr-2 text-slate-400"></i>Nama Bank / E-Wallet
                                </label>
                                <select name="nama_bank"
                                    class="w-full rounded-2xl border-2 border-slate-200 px-5 py-3.5 text-base outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 transition-all duration-200 bg-white">
                                    <option value="">-- Pilih Bank / E-Wallet --</option>
                                    @php
                                        $banks = ['BCA', 'BRI', 'BNI', 'Mandiri', 'BSI', 'CIMB Niaga', 'Danamon', 'GoPay', 'OVO', 'DANA', 'ShopeePay', 'LinkAja', 'QRIS'];
                                        $selectedBank = $edit->nama_bank ?? '';
                                    @endphp
                                    @foreach($banks as $bank)
                                        <option value="{{ $bank }}" {{ $selectedBank === $bank ? 'selected' : '' }}>{{ $bank }}</option>
                                    @endforeach
                                    @if($selectedBank && !in_array($selectedBank, $banks))
                                        <option value="{{ $selectedBank }}" selected>{{ $selectedBank }}</option>
                                    @endif
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-widest mb-2">
                                    <i class="fa fa-hashtag mr-2 text-slate-400"></i>Nomor Rekening / Nomor HP
                                </label>
                                <input name="no_rekening" value="{{ $edit->no_rekening ?? '' }}"
                                    placeholder="Contoh: 1234567890 atau 0812xxxxxxxx"
                                    class="w-full rounded-2xl border-2 border-slate-200 px-5 py-3.5 text-base outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 transition-all duration-200 font-mono placeholder:text-slate-400">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-widest mb-2">
                                    <i class="fa fa-user mr-2 text-slate-400"></i>Atas Nama Rekening
                                </label>
                                <input name="atas_nama_rekening" value="{{ $edit->atas_nama_rekening ?? '' }}"
                                    placeholder="Nama sesuai rekening bank"
                                    class="w-full rounded-2xl border-2 border-slate-200 px-5 py-3.5 text-base outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 transition-all duration-200 placeholder:text-slate-400">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-widest mb-2">
                                    <i class="fa fa-comment mr-2 text-slate-400"></i>Catatan Pembayaran <span class="text-slate-300 font-normal">(opsional)</span>
                                </label>
                                <input name="catatan_pembayaran" value="{{ $edit->catatan_pembayaran ?? '' }}"
                                    placeholder="Contoh: DP minimal 50% dari total"
                                    class="w-full rounded-2xl border-2 border-slate-200 px-5 py-3.5 text-base outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 transition-all duration-200 placeholder:text-slate-400">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ======= TOMBOL SIMPAN ======= --}}
                <div class="pt-2">
                    <button type="submit"
                        class="group relative overflow-hidden rounded-2xl bg-gradient-to-r from-teal-600 via-teal-500 to-emerald-500 text-white px-8 py-4 font-bold text-lg shadow-xl shadow-teal-600/30 transition-all duration-300 hover:shadow-2xl hover:-translate-y-0.5 active:scale-[0.98]">
                        <span class="relative flex items-center justify-center gap-2">
                            <i class="fa fa-save"></i>
                            Simpan Pengaturan
                        </span>
                        <div class="absolute inset-0 bg-gradient-to-r from-emerald-500 via-teal-500 to-teal-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </button>
                </div>
            </form>
        </div>

        {{-- Kolom Kanan: QR Code --}}
        <div class="lg:col-span-1">
            <div class="rounded-3xl border border-teal-200 overflow-hidden shadow-sm bg-white">
                <div class="bg-gradient-to-r from-teal-600 via-teal-500 to-emerald-500 px-7 py-5 flex items-center gap-3">
                    <div class="h-12 w-12 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center">
                        <i class="fa fa-qrcode text-white text-xl"></i>
                    </div>
                    <span class="text-white font-bold text-base">QR Code Menu</span>
                </div>
                <div class="p-7 text-center">
                    @if(Auth::user()->umkm && Auth::user()->umkm->kode_umkm)
                        @php
                            $menuUrl = route('landing.umkm-menu', Auth::user()->umkm->kode_umkm);
                            $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=" . urlencode($menuUrl);
                        @endphp
                        <div class="bg-gradient-to-br from-slate-50 to-slate-100 p-5 rounded-2xl border border-slate-200 inline-block mb-5 shadow-inner">
                            <img src="{{ $qrUrl }}" alt="QR Code Menu" class="mx-auto w-52 h-52 rounded-xl shadow-md bg-white">
                        </div>
                        <h4 class="text-base font-bold text-slate-800 mb-1">{{ Auth::user()->umkm->nama_umkm }}</h4>
                        <p class="text-sm text-slate-500 mb-5 leading-relaxed">Scan QR untuk mengakses menu pelanggan tanpa login.</p>
                        <div class="space-y-3">
                            <a href="{{ $menuUrl }}" target="_blank" class="block w-full text-center rounded-2xl border-2 border-teal-200 hover:bg-teal-50 text-teal-700 font-bold py-3 px-5 text-sm transition-all duration-200 hover:border-teal-400 hover:-translate-y-0.5">
                                <i class="fa fa-external-link mr-2"></i> Buka Link Menu
                            </a>
                            <a href="{{ route('info.print-qr') }}" target="_blank" class="block w-full text-center rounded-2xl bg-gradient-to-r from-teal-600 via-teal-500 to-emerald-500 hover:via-teal-600 text-white font-bold py-3 px-5 text-sm shadow-lg shadow-teal-600/20 transition-all duration-200 hover:shadow-xl hover:-translate-y-0.5 active:scale-[0.98]">
                                <i class="fa fa-print mr-2"></i> Cetak QR Code Meja
                            </a>
                        </div>
                    @else
                        <div class="text-slate-400 py-10 text-sm">
                            <div class="h-14 w-14 rounded-full bg-amber-100 flex items-center justify-center mx-auto mb-4">
                                <i class="fa fa-warning text-amber-500 text-2xl"></i>
                            </div>
                            Kode UMKM tidak ditemukan.<br>Hubungi Administrator.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-page-card>
@endsection
