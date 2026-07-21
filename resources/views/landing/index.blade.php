@php
    $namaToko = 'KASIR MULTI-UMKM';
    $tagline = 'Sistem kasir & POS terpadu untuk pelaku usaha mikro, kecil, dan menengah (UMKM).';
    $menuCount = $menu_count ?? 0;
    $userCount = $user_count ?? 0;
@endphp
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $namaToko }} &mdash; Solusi POS UMKM Terintegrasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: { navy: '#022c22', teal: '#0d9488', 'teal-light': '#14b8a6' }
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');
        body {
            font-family: 'Outfit', sans-serif;
        }
        .glass-card { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1); }
        .hero-pattern { background-image: radial-gradient(circle at 2px 2px, rgba(99, 102, 241, 0.15) 1px, transparent 0); background-size: 40px 40px; }
        [x-cloak] { display: none !important; }
        
        /* Hide scrollbars */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body class="bg-white text-slate-800 antialiased" x-data="{ mobileMenuOpen: false }">
    {{-- Nav --}}
    <nav id="publicNav" class="fixed top-0 inset-x-0 z-50 transition-all duration-300 border-b border-transparent">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
            <a href="{{ route('landing') }}" class="flex items-center gap-2 font-bold text-white group shrink-0">
                <span class="h-10 w-10 rounded-xl bg-teal-500 flex items-center justify-center shadow-lg shadow-teal-500/40 group-hover:rotate-12 transition"><i class="fa fa-shopping-bag"></i></span>
                <span class="text-xl tracking-tight">{{ $namaToko }}</span>
            </a>
            
            {{-- Desktop Menu --}}
            <div class="hidden lg:flex items-center gap-8 text-sm font-medium">
                <a href="#mitra" class="text-slate-200 hover:text-teal-400 transition">Mitra Kami</a>
                <a href="#fitur" class="text-slate-200 hover:text-teal-400 transition">Fitur</a>
                <a href="#solusi" class="text-slate-200 hover:text-teal-400 transition">Solusi</a>
                <a href="#analitik" class="text-slate-200 hover:text-teal-400 transition">Analitik</a>
                <a href="#faq" class="text-slate-200 hover:text-teal-400 transition">FAQ</a>
            </div>

            <div class="flex items-center gap-3">
                <div class="hidden sm:flex items-center gap-3">
                    <a href="{{ route('login') }}" class="text-sm text-slate-200 hover:text-white font-medium px-4">Masuk</a>
                    <a href="{{ route('register') }}" class="rounded-xl px-5 py-2.5 bg-teal-600 text-white text-sm font-bold hover:bg-teal-500 transition shadow-lg shadow-teal-600/30">Mulai Gratis</a>
                </div>
                
                {{-- Mobile Menu Toggle --}}
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden text-white p-2 rounded-xl bg-white/10 hover:bg-white/20 transition">
                    <i class="fa" :class="mobileMenuOpen ? 'fa-times' : 'fa-bars'"></i>
                </button>
            </div>
        </div>

        {{-- Mobile Menu Overlay --}}
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-cloak 
             class="lg:hidden bg-slate-900 border-b border-white/10 p-6 space-y-4">
            <div class="flex flex-col gap-4 text-center">
                <a @click="mobileMenuOpen = false" href="#mitra" class="text-slate-300 hover:text-teal-400 font-medium">Mitra Kami</a>
                <a @click="mobileMenuOpen = false" href="#fitur" class="text-slate-300 hover:text-teal-400 font-medium">Fitur</a>
                <a @click="mobileMenuOpen = false" href="#solusi" class="text-slate-300 hover:text-teal-400 font-medium">Solusi</a>
                <a @click="mobileMenuOpen = false" href="#analitik" class="text-slate-300 hover:text-teal-400 font-medium">Analitik</a>
                <a @click="mobileMenuOpen = false" href="#faq" class="text-slate-300 hover:text-teal-400 font-medium">FAQ</a>
                <div class="pt-4 border-t border-white/5 flex flex-col gap-3">
                    <a href="{{ route('login') }}" class="text-slate-300 font-medium">Masuk</a>
                    <a href="{{ route('register') }}" class="rounded-xl px-5 py-3 bg-teal-600 text-white font-bold">Daftar Sekarang</a>
                </div>
            </div>
        </div>
    </nav>

    {{-- Hero --}}
    <section class="relative pt-32 pb-24 lg:pt-48 lg:pb-40 bg-brand-navy overflow-hidden">
        <div class="absolute inset-0 hero-pattern"></div>
        <div class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/4 w-[600px] h-[600px] bg-teal-500/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 translate-y-1/2 -translate-x-1/4 w-[600px] h-[600px] bg-emerald-500/10 rounded-full blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">
                <div class="text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 rounded-full bg-teal-500/10 border border-teal-500/20 px-4 py-2 text-sm font-semibold text-teal-400 mb-8 animate-bounce">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-teal-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-teal-500"></span>
                        </span>
                        Aplikasi Multi-Tenant &amp; POS UMKM Terlengkap
                    </div>
                    <h1 class="text-4xl sm:text-5xl lg:text-7xl font-extrabold text-white leading-[1.1] tracking-tight">
                        Scale Up Bisnis <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-400 to-emerald-400">UMKM Anda</span>
                    </h1>
                    <p class="mt-8 text-lg sm:text-xl text-slate-400 leading-relaxed max-w-2xl mx-auto lg:mx-0">
                        Kelola penjualan multi-toko dengan satu platform. Akses digital menu mandiri via QR Code, pantau stok barang real-time, dan optimalkan rule belanja dengan Apriori.
                    </p>
                    <div class="mt-10 flex flex-col sm:flex-row justify-center lg:justify-start gap-4">
                        <a href="#mitra" class="group inline-flex items-center justify-center gap-3 rounded-2xl bg-teal-600 hover:bg-teal-500 px-8 py-4 text-white font-bold shadow-2xl shadow-teal-600/40 transition transform hover:-translate-y-1">
                            Pilih Toko Mitra <i class="fa fa-arrow-right group-hover:translate-x-1 transition"></i>
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-3 rounded-2xl bg-white/5 border border-white/10 hover:bg-white/10 px-8 py-4 text-white font-bold backdrop-blur transition">
                            Mulai Registrasi
                        </a>
                    </div>
                    <div class="mt-12 grid grid-cols-3 gap-4 sm:gap-8 border-t border-white/5 pt-8 max-w-lg mx-auto lg:mx-0 text-center lg:text-left">
                        <div>
                            <p class="text-xl sm:text-2xl font-bold text-white">{{ number_format($menuCount) }}+</p>
                            <p class="text-[10px] sm:text-sm text-slate-500 uppercase font-bold tracking-wider">Total Menu</p>
                        </div>
                        <div>
                            <p class="text-xl sm:text-2xl font-bold text-white">{{ number_format($userCount) }}+</p>
                            <p class="text-[10px] sm:text-sm text-slate-500 uppercase font-bold tracking-wider">Mitra Bisnis</p>
                        </div>
                        <div>
                            <p class="text-xl sm:text-2xl font-bold text-white">99.9%</p>
                            <p class="text-[10px] sm:text-sm text-slate-500 uppercase font-bold tracking-wider">Uptime</p>
                        </div>
                    </div>
                </div>
                <div class="relative lg:block hidden">
                    <div class="relative z-20 glass-card rounded-3xl p-8 shadow-2xl rotate-3 hover:rotate-0 transition-transform duration-500">
                        <div class="flex items-center justify-between mb-8 border-b border-white/10 pb-4">
                            <div class="flex gap-2">
                                <span class="h-3 w-3 rounded-full bg-red-500"></span>
                                <span class="h-3 w-3 rounded-full bg-amber-500"></span>
                                <span class="h-3 w-3 rounded-full bg-emerald-500"></span>
                            </div>
                            <span class="text-xs text-slate-400 font-mono">wpf_pos_dashboard</span>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="bg-teal-500/10 rounded-2xl p-4 border border-teal-500/20">
                                <p class="text-[10px] uppercase tracking-wider text-teal-400 font-bold mb-1">Total Sales</p>
                                <p class="text-xl font-bold text-white">Rp 12.5M</p>
                            </div>
                            <div class="bg-emerald-500/10 rounded-2xl p-4 border border-emerald-500/20">
                                <p class="text-[10px] uppercase tracking-wider text-emerald-400 font-bold mb-1">Orders</p>
                                <p class="text-xl font-bold text-white">1,284</p>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div class="h-2 w-full bg-white/5 rounded-full overflow-hidden">
                                <div class="h-full w-3/4 bg-gradient-to-r from-teal-500 to-emerald-500"></div>
                            </div>
                            <div class="h-2 w-full bg-white/5 rounded-full overflow-hidden">
                                <div class="h-full w-1/2 bg-gradient-to-r from-emerald-500 to-teal-500"></div>
                            </div>
                        </div>
                    </div>
                    <div class="absolute -top-10 -left-10 w-32 h-32 bg-teal-500/20 rounded-full blur-2xl"></div>
                    <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-emerald-500/20 rounded-full blur-2xl"></div>
                </div>
            </div>
        </div>
    </section>

    {{-- Trusted Section --}}
    <section class="py-12 bg-white border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4">
            <p class="text-center text-sm font-semibold text-slate-400 uppercase tracking-[0.2em] mb-8">Solusi Untuk Berbagai Jenis Usaha</p>
            <div class="flex flex-wrap justify-center items-center gap-8 md:gap-16 opacity-50 grayscale hover:opacity-100 transition duration-500">
                <div class="flex items-center gap-2 font-bold text-xl"><i class="fa fa-coffee text-teal-600"></i> Coffee Shop</div>
                <div class="flex items-center gap-2 font-bold text-xl"><i class="fa fa-cutlery text-teal-600"></i> Restoran</div>
                <div class="flex items-center gap-2 font-bold text-xl"><i class="fa fa-shopping-basket text-teal-600"></i> Minimarket</div>
                <div class="flex items-center gap-2 font-bold text-xl"><i class="fa fa-scissors text-teal-600"></i> Barbershop</div>
                <div class="flex items-center gap-2 font-bold text-xl"><i class="fa fa-bolt text-teal-600"></i> Jasa & Laundry</div>
            </div>
        </div>
    </section>

    {{-- Mitra Partner Kami --}}
    <section id="mitra" class="py-24 bg-slate-50 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <div class="inline-flex items-center gap-2 rounded-full bg-teal-50 text-teal-700 px-4 py-2 text-sm font-bold mb-6">
                    <i class="fa fa-users"></i> Mitra Partner Kami
                </div>
                <h2 class="text-4xl font-extrabold text-slate-900 leading-tight">
                    Mitra UMKM <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-600 to-emerald-500 font-sans font-black">Terdaftar</span>
                </h2>
                <p class="mt-4 text-lg text-slate-600">
                    Jelajahi daftar toko mitra kami yang telah menggunakan layanan KASIR MULTI-UMKM. Scan atau buka menu secara mandiri.
                </p>
            </div>

            <div class="mb-8 max-w-xl mx-auto">
                <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm">
                    <div class="flex items-center gap-2 mb-3">
                        <i class="fa fa-search text-teal-500"></i>
                        <h3 class="font-bold text-slate-700">Lupa / Cari Kode UMKM Toko?</h3>
                    </div>
                    <input type="text" id="landing-lookup-search" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-800 placeholder-slate-400 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 outline-none transition" placeholder="Ketik nama toko atau nama pemilik...">
                </div>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8" id="landing-lookup-list">
                @forelse($umkms ?? [] as $u)
                    @php
                        $profilUMKM = $u->profilToko;
                        $waUMKMRaw = preg_replace('/\D/', '', $profilUMKM->telepon_toko ?? $u->telepon ?? '');
                        if (str_starts_with($waUMKMRaw, '0')) {
                            $waUMKMRaw = '62' . substr($waUMKMRaw, 1);
                        }
                        $waUMKMUrl = $waUMKMRaw ? 'https://wa.me/' . $waUMKMRaw . '?text=' . urlencode("Halo, saya ingin menanyakan produk / booking di " . $u->nama_umkm) : '#';
                        $menuUMKMUrl = route('landing.umkm-menu', $u->kode_umkm);
                    @endphp
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/60 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between landing-lookup-item" data-name="{{ strtolower($u->nama_umkm) }} {{ strtolower($u->nama_pemilik) }} {{ strtolower($u->kode_umkm) }}">
                        <div>
                            {{-- Foto Toko --}}
                            <div class="relative w-full h-44 rounded-2xl overflow-hidden mb-5 bg-slate-50 border border-slate-100 shadow-sm group/img">
                                @if($u->store_photo)
                                    <img src="{{ asset('assets/image/user/' . $u->store_photo) }}" alt="{{ $u->nama_umkm }}" class="w-full h-full object-cover group-hover/img:scale-105 transition-transform duration-500">
                                @else
                                    {{-- Premium Gradient Placeholder with Store Icon --}}
                                    <div class="w-full h-full bg-gradient-to-br from-teal-50 to-emerald-50 flex flex-col items-center justify-center gap-2">
                                        <div class="h-12 w-12 rounded-full bg-white flex items-center justify-center text-teal-600 shadow-sm border border-teal-50">
                                            <i class="fa fa-shopping-bag text-lg"></i>
                                        </div>
                                        <span class="text-[10px] text-teal-700/60 font-bold uppercase tracking-wider">KASIR MULTI-UMKM</span>
                                    </div>
                                @endif
                                
                                {{-- Floating Code Badge --}}
                                <span class="absolute top-3 left-3 text-[10px] font-bold bg-white/95 text-teal-800 px-2.5 py-1 rounded-full uppercase tracking-wider font-mono shadow-sm">
                                    {{ $u->kode_umkm }}
                                </span>

                                {{-- Floating Status and Copy Button --}}
                                <div class="absolute top-3 right-3 flex items-center gap-1.5">
                                    <span class="text-[10px] font-bold bg-white/95 text-teal-850 px-2.5 py-1 rounded-full uppercase tracking-wider shadow-sm flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Aktif
                                    </span>
                                    <button type="button" onclick="navigator.clipboard.writeText('{{ $u->kode_umkm }}'); alert('Kode {{ $u->kode_umkm }} disalin!');" class="bg-white/95 hover:bg-teal-50 text-teal-700 p-1.5 rounded-full shadow-sm flex items-center justify-center transition" title="Salin Kode UMKM">
                                        <i class="fa fa-clone text-[10px]"></i>
                                    </button>
                                </div>
                            </div>

                            <h4 class="text-lg font-bold text-slate-900 mb-2 truncate">{{ $u->nama_umkm }}</h4>
                            <p class="text-xs text-slate-500 line-clamp-2 mb-4"><i class="fa fa-map-marker text-teal-500 mr-1"></i> {{ $u->alamat_umkm }}</p>
                            <div class="border-t border-slate-100 pt-4 flex gap-4 text-xs text-slate-505 mb-6">
                                <div>
                                    <span class="block text-[10px] text-slate-400 uppercase tracking-wider font-bold">Pemilik</span>
                                    <span class="font-semibold text-slate-700">{{ $u->nama_pemilik }}</span>
                                </div>
                                <div>
                                    <span class="block text-[10px] text-slate-400 uppercase tracking-wider font-bold">Telepon</span>
                                    <span class="font-semibold text-slate-700">{{ $u->telepon }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <a href="{{ $menuUMKMUrl }}" class="w-full text-center rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-bold py-2.5 text-xs shadow-md shadow-teal-600/10 active:scale-95 transition flex items-center justify-center gap-1.5">
                                <i class="fa fa-cutlery"></i> Buka Menu
                            </a>
                            @if($waUMKMRaw)
                                <a href="{{ $waUMKMUrl }}" target="_blank" class="w-full text-center rounded-xl border border-emerald-200 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold py-2.5 text-xs active:scale-95 transition flex items-center justify-center gap-1.5">
                                    <i class="fa fa-whatsapp"></i> Chat WA
                                </a>
                            @else
                                <span class="w-full text-center rounded-xl bg-slate-100 text-slate-400 font-semibold py-2.5 text-xs select-none">
                                    No WA N/A
                                </span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12 text-slate-400">
                        <i class="fa fa-store-o text-slate-300 text-5xl mb-3 block"></i>
                        Belum ada mitra UMKM terdaftar yang aktif.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Fitur --}}
    <section id="fitur" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-teal-600 font-bold text-sm uppercase tracking-widest mb-3">Fitur Ekosistem</h2>
                <h3 class="text-4xl font-extrabold text-slate-900 leading-tight">Satu Aplikasi, <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-600 to-teal-500">Sejuta Kemudahan</span></h3>
                <p class="mt-4 text-lg text-slate-600">Sistem multi-tenant dirancang khusus untuk membagi workspace data UMKM secara aman & optimal.</p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                @foreach ([
                    ['fa-desktop', 'Point of Sale (POS)', 'Antarmuka kasir premium cepat dengan opsi pembayaran, kembalian otomatis, dan rekam history transaksi.'],
                    ['fa-qrcode', 'QR Menu Pelanggan', 'Pelanggan bisa langsung memesan menu tanpa perlu login dengan memindai QR Code di meja stand.'],
                    ['fa-cubes', 'Manajemen Stok', 'Inventarisasi terpusat dengan laporan bahan baku dan stok menu yang otomatis berkurang saat terjual.'],
                    ['fa-line-chart', 'Laporan Keuangan', 'Catatan keuangan otomatis terbagi per ledger, laba bersih kotor, kas harian, bulanan.'],
                    ['fa-sitemap', 'Rekomendasi Apriori', 'Analisis basket belanja pintar. Dapatkan usulan menu bundling terlaris berdasarkan kecocokan pola transaksi.'],
                    ['fa-shield', 'Keamanan Multi-Tenant', 'Data master (kategori, menu, user, transaksi) terisolasi penuh antar-tenant menggunakan Global Query Scope.'],
                ] as [$icon, $title, $desc])
                    <div class="group bg-slate-50 rounded-3xl p-8 border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300">
                        <div class="h-14 w-14 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center text-2xl mb-6 group-hover:bg-teal-600 group-hover:text-white transition-colors duration-300">
                            <i class="fa {{ $icon }}"></i>
                        </div>
                        <h4 class="text-xl font-bold text-slate-900 mb-3">{{ $title }}</h4>
                        <p class="text-slate-600 leading-relaxed text-sm">{{ $desc }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Solusi --}}
    <section id="solusi" class="py-24 bg-slate-50 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid lg:grid-cols-2 gap-20 items-center">
                <div class="relative">
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full h-full bg-teal-100 rounded-full blur-[100px] opacity-50"></div>
                    <img src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?auto=format&fit=crop&q=80&w=800" alt="Cashier" class="relative z-10 rounded-[2.5rem] shadow-2xl">
                    <div class="absolute -bottom-8 -right-8 z-20 bg-white rounded-3xl p-6 shadow-xl border border-slate-100 max-w-xs">
                        <div class="flex items-center gap-4">
                            <div class="h-12 w-12 rounded-full bg-teal-100 text-teal-600 flex items-center justify-center text-xl">
                                <i class="fa fa-check"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-900">Workspace Terisolasi</p>
                                <p class="text-xs text-slate-500">Global tenant scope</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <h3 class="text-teal-600 font-bold text-sm uppercase tracking-widest mb-3">Kemudahan Integrasi</h3>
                    <h2 class="text-4xl font-extrabold text-slate-900 leading-tight mb-6">Pantau Penjualan <span class="text-teal-600 font-sans">Kapan Saja</span></h2>
                    <p class="text-lg text-slate-600 mb-8 leading-relaxed">
                        Tiap pemilik toko memiliki otorisasi penuh atas pengaturan nama bank, rekening transfer, diskon pajak, cetak QR meja, dan manajemen kasir mereka sendiri.
                    </p>
                    <ul class="space-y-4">
                        @foreach ([
                            'Pendaftaran UMKM instan ter-generate otomatis',
                            'Dashboard panel analytics rule Apriori terpisah',
                            'Otorisasi kasir & admin UMKM yang fleksibel',
                            'Koneksi booking instan WhatsApp'
                        ] as $item)
                            <li class="flex items-center gap-3 font-medium text-slate-700">
                                <div class="h-6 w-6 rounded-full bg-teal-600 text-white flex items-center justify-center text-[10px]">
                                    <i class="fa fa-check"></i>
                                </div>
                                {{ $item }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- Analitik --}}
    <section id="analitik" class="py-24 bg-slate-900 text-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 relative z-10">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div>
                    <h3 class="text-teal-400 font-bold text-sm uppercase tracking-widest mb-3">Analisis Pola Belanja</h3>
                    <h2 class="text-4xl font-extrabold leading-tight mb-6">Asosiasi Asosiatif Produk dengan <span class="text-teal-400">Algoritma Apriori</span></h2>
                    <p class="text-slate-400 text-lg mb-8 leading-relaxed">
                        POS kami terintegrasi dengan perhitungan data mining. Dapatkan relasi otomatis antar menu dengan minimum support dan confidence tertentu.
                    </p>
                    <div class="grid sm:grid-cols-2 gap-6">
                        <div class="bg-white/5 rounded-2xl p-6 border border-white/10">
                            <h5 class="font-bold text-white mb-2">Rule Teruji</h5>
                            <p class="text-sm text-slate-400">Dapatkan visualisasi rule asosiasi terakurat per periode bulan.</p>
                        </div>
                        <div class="bg-white/5 rounded-2xl p-6 border border-white/10">
                            <h5 class="font-bold text-white mb-2">Bundle Pintar</h5>
                            <p class="text-sm text-slate-400">Rekomendasikan produk secara realtime saat customer memilih menu.</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white/5 border border-white/10 rounded-[3rem] p-10 backdrop-blur-sm">
                    <div class="space-y-6">
                        <div class="flex items-center justify-between border-b border-white/5 pb-4">
                            <span class="text-sm font-bold">Market Basket Analysis</span>
                            <span class="text-xs text-teal-400">Live Process</span>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-center gap-4 bg-teal-500/10 p-4 rounded-2xl border border-teal-500/20">
                                <span class="px-2 py-1 bg-teal-500 rounded text-[10px] font-bold">IF</span>
                                <span class="text-sm font-medium">Kopi Susu</span>
                                <i class="fa fa-arrow-right text-teal-500"></i>
                                <span class="px-2 py-1 bg-emerald-500 rounded text-[10px] font-bold">THEN</span>
                                <span class="text-sm font-medium">Roti Bakar</span>
                                <span class="ml-auto text-xs font-bold text-teal-400">85% Conf.</span>
                            </div>
                            <div class="flex items-center gap-4 bg-white/5 p-4 rounded-2xl border border-white/5">
                                <span class="px-2 py-1 bg-teal-50 rounded text-[10px] font-bold text-teal-700">IF</span>
                                <span class="text-sm font-medium">Indomie Goreng</span>
                                <i class="fa fa-arrow-right text-teal-500"></i>
                                <span class="px-2 py-1 bg-emerald-500 rounded text-[10px] font-bold">THEN</span>
                                <span class="text-sm font-medium">Es Teh Manis</span>
                                <span class="ml-auto text-xs font-bold text-teal-400">92% Conf.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- FAQ --}}
    <section id="faq" class="py-24 bg-white">
        <div class="max-w-4xl mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-extrabold text-slate-900">Pertanyaan Umum</h2>
                <p class="mt-4 text-slate-600">Jawaban singkat seputar sistem multi-tenant KASIR MULTI-UMKM.</p>
            </div>
            <div class="space-y-4">
                @foreach ([
                    ['Apakah data antar UMKM akan tercampur?', 'Tidak. Kami menggunakan Laravel Global Query Scope yang menyaring data umkm_id di setiap query database secara otomatis.'],
                    ['Bagaimana pelanggan memesan tanpa login?', 'Pelanggan cukup memindai QR Code stand meja toko. Sistem akan membuka menu toko dan menyimpan keranjang secara lokal.'],
                    ['Apakah rule rekomendasi dapat diatur?', 'Ya. Pemilik toko dapat mengunggah dataset transaksi, melakukan perhitungan Apriori dengan kriteria support/confidence sendiri, lalu menerapkannya.'],
                ] as [$q, $a])
                    <div class="border border-slate-100 rounded-2xl overflow-hidden">
                        <details class="group">
                            <summary class="flex justify-between items-center p-6 cursor-pointer font-bold text-slate-800 list-none group-open:bg-slate-50 transition-colors">
                                {{ $q }}
                                <span class="transition-transform duration-300 group-open:rotate-180"><i class="fa fa-chevron-down"></i></span>
                            </summary>
                            <div class="p-6 text-slate-600 text-sm leading-relaxed border-t border-slate-50">
                                {{ $a }}
                            </div>
                        </details>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-24 relative overflow-hidden">
        <div class="absolute inset-0 bg-teal-600"></div>
        <div class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/4 w-96 h-96 bg-teal-400/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 translate-y-1/2 -translate-x-1/4 w-96 h-96 bg-emerald-400/20 rounded-full blur-3xl"></div>
        
        <div class="max-w-5xl mx-auto px-4 text-center relative z-10">
            <h2 class="text-4xl md:text-5xl font-extrabold text-white leading-tight mb-8">Modernisasi Pengelolaan Toko UMKM Anda</h2>
            <p class="text-xl text-teal-50 mb-12 max-w-2xl mx-auto opacity-90">
                Gunakan platform multi-tenant KASIR MULTI-UMKM sekarang secara gratis dan nikmati kemudahan fiturnya.
            </p>
            <div class="flex flex-wrap justify-center gap-6">
                <a href="{{ route('register') }}" class="px-10 py-5 bg-white text-teal-600 font-extrabold rounded-2xl shadow-2xl shadow-black/20 hover:scale-105 transition transform active:scale-95">
                    Daftar Akun Mitra
                </a>
                <a href="{{ route('login') }}" class="px-10 py-5 bg-teal-700 text-white font-extrabold rounded-2xl border border-teal-500/30 hover:bg-teal-800 transition">
                    Masuk ke Dashboard
                </a>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-slate-900 text-slate-400 pt-20 pb-10">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid md:grid-cols-4 gap-12 mb-16">
                <div class="col-span-1 md:col-span-2">
                    <a href="#" class="flex items-center gap-2 font-bold text-white text-2xl mb-6">
                        <span class="h-10 w-10 rounded-xl bg-teal-500 flex items-center justify-center shadow-lg shadow-teal-500/40"><i class="fa fa-shopping-bag"></i></span>
                        {{ $namaToko }}
                    </a>
                    <p class="max-w-md mb-8 leading-relaxed">
                        Sistem manajemen Multi-Tenant POS modern yang didesain khusus untuk mendukung operasional retail dan Food &amp; Beverage UMKM.
                    </p>
                </div>
                <div>
                    <h5 class="text-white font-bold mb-6">Navigasi</h5>
                    <ul class="space-y-4 text-sm">
                        <li><a href="#mitra" class="hover:text-teal-400 transition">Mitra Kami</a></li>
                        <li><a href="#fitur" class="hover:text-teal-400 transition">Fitur Utama</a></li>
                        <li><a href="#solusi" class="hover:text-teal-400 transition">Solusi Bisnis</a></li>
                        <li><a href="#analitik" class="hover:text-teal-400 transition">Analisis Data</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="text-white font-bold mb-6">Kontak</h5>
                    <ul class="space-y-4 text-sm">
                        <li class="flex items-start gap-3">
                            <i class="fa fa-map-marker mt-1 text-teal-500"></i>
                            <span>Bekasi, Indonesia</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fa fa-envelope text-teal-500"></i>
                            <span>support@wpfpos.id</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-white/5 pt-10 text-center text-xs">
                <p>&copy; {{ date('Y') }} <strong class="text-white">{{ $namaToko }}</strong>. All rights reserved.</p>
                <p class="mt-2 text-slate-500 italic">{{ $tagline }}</p>
            </div>
        </div>
    </footer>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        (function () {
            var nav = document.getElementById('publicNav');
            if (!nav) return;
            function onScroll() {
                if (window.scrollY > 40) {
                    nav.classList.add('bg-brand-navy/90', 'backdrop-blur-md', 'border-white/5', 'shadow-2xl', 'py-3');
                    nav.classList.remove('py-4', 'border-transparent');
                } else {
                    nav.classList.remove('bg-brand-navy/90', 'backdrop-blur-md', 'border-white/5', 'shadow-2xl', 'py-3');
                    nav.classList.add('py-4', 'border-transparent');
                }
            }
            window.addEventListener('scroll', onScroll);
            onScroll();
        })();

        // Search functionality for Mitra UMKM
        document.getElementById('landing-lookup-search').addEventListener('input', function(e) {
            var q = e.target.value.toLowerCase();
            var items = document.querySelectorAll('.landing-lookup-item');
            items.forEach(function(item) {
                var text = item.getAttribute('data-name');
                if (text.indexOf(q) > -1) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
