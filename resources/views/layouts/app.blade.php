@php
    $isAdmin = auth()->check() && (auth()->user()->level ?? '') === 'Admin';
    $isSuperAdmin = auth()->check() && (auth()->user()->level ?? '') === 'SuperAdmin';
    $userName = auth()->user()->nama_user ?? auth()->user()->name ?? 'Pengguna';
    $orderTotal = $orderBadgeTotal ?? 0;
    $orderDitempat = $orderBadgeDitempat ?? 0;
    $pageTitle = $title ?? ($title_web ?? 'Dashboard');
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle }} &mdash; KASIR MULTI-UMKM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        sidebar: { 
                            DEFAULT: '#0f172a', 
                            hover: '#1e293b', 
                            active: '#0d9488',
                            accent: '#14b8a6'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        .sidebar-gradient {
            background: linear-gradient(180deg, #0f172a 0%, #111827 100%);
        }
        .nav-item-transition {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .active-glow {
            box-shadow: 0 0 15px rgba(13, 148, 136, 0.3);
        }
        
        @media print {
            /* Hide navbar and sidebar */
            nav, aside, .sidebar { display: none !important; }
            .no-print { display: none !important; }
            
            /* Full width untuk print */
            body, main, .container { margin: 0; padding: 0; background: white !important; }
            
            /* Atur halaman */
            @page { 
                margin: 10mm; 
                size: A4;
            }
            
            /* Warna di print */
            * { 
                box-shadow: none !important;
                color-adjust: exact !important;
                -webkit-print-color-adjust: exact !important;
            }
            
            /* Tabel print */
            table { width: 100% !important; border-collapse: collapse; }
            th { background: #0f172a !important; color: white !important; padding: 8px; }
            td { padding: 6px; border: 1px solid #ccc; }
            
            /* Hindari page break di tengah tabel */
            tbody { page-break-inside: avoid; }
            tr { page-break-inside: avoid; }
            
            /* Simpan warna background */
            .bg-slate-50, .bg-slate-100, .bg-blue-100, .bg-emerald-100, .bg-amber-100 {
                background-color: inherit !important;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-100 text-slate-800 antialiased" x-data="{ sidebarOpen: false, userOpen: false, openDropdown: '{{ (request()->routeIs('menu.*', 'kategori.*', 'customer.*', 'users.*') && !request()->routeIs('menu.stok', 'menu.persediaan')) ? 'master' : (request()->routeIs('menu.stok', 'menu.persediaan') ? 'stok' : (request()->routeIs('order.*') ? 'order' : (request()->routeIs('laporan.*') ? 'laporan' : (request()->routeIs('algoritma.*') ? 'apriori' : '')))) }}' }">
    <div class="flex min-h-screen">
        {{-- Overlay mobile --}}
        <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
            class="fixed inset-0 z-40 bg-slate-900/60 lg:hidden" x-transition.opacity></div>

        {{-- Sidebar --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 w-72 sidebar-gradient text-slate-300 transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:flex lg:flex-col shadow-2xl border-r border-white/5">
            
            {{-- Brand Logo --}}
            <div class="flex items-center justify-between h-24 px-6 border-b border-white/5">
                <a href="{{ $isAdmin ? route('home') : route('kasir.index') }}" class="flex items-center gap-4 group min-w-0 flex-1 mr-2">
                    <div class="h-14 w-14 rounded-2xl bg-teal-500 flex items-center justify-center shadow-lg shadow-teal-500/20 group-hover:rotate-6 transition-transform shrink-0">
                        <i class="fa fa-shopping-bag text-white text-2xl"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        @php
                            $brandName = 'KASIR MULTI UMKM';
                            $brandSub = 'SISTEM POS';
                            if (auth()->check() && auth()->user()->umkm) {
                                $brandName = auth()->user()->umkm->nama_umkm;
                                $brandSub = auth()->user()->umkm->kode_umkm;
                            } elseif (auth()->check() && auth()->user()->level === 'SuperAdmin') {
                                $brandName = 'KASIR MULTI UMKM';
                                $brandSub = 'SUPER ADMIN';
                            }
                        @endphp
                        <span class="block font-black text-white text-lg tracking-tight leading-none truncate" title="{{ $brandName }}">{{ $brandName }}</span>
                        <span class="block text-sm font-mono font-bold text-teal-400 tracking-wider mt-2 uppercase truncate" title="{{ $brandSub }}">{{ $brandSub }}</span>
                    </div>
                </a>
                <button type="button" @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white p-2 rounded-lg hover:bg-white/5">
                    <i class="fa fa-times"></i>
                </button>
            </div>

            <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-1.5 custom-scrollbar">
                @if ($isSuperAdmin)
                    <a href="{{ route('superadmin.index') }}"
                        class="nav-item-transition flex items-center gap-3.5 rounded-xl px-4 py-3 {{ request()->routeIs('superadmin.*') ? 'bg-sidebar-active text-white active-glow' : 'hover:bg-white/5 text-slate-200 hover:text-white' }}">
                        <i class="fa fa-building w-5 text-center text-lg"></i>
                        <span class="font-black tracking-wide">MANAJEMEN UMKM</span>
                    </a>
                @endif

                @if ($isAdmin)
                    <a href="{{ route('home') }}"
                        class="nav-item-transition flex items-center gap-3.5 rounded-xl px-4 py-3 {{ request()->routeIs('home') ? 'bg-sidebar-active text-white active-glow' : 'hover:bg-white/5 text-slate-200 hover:text-white' }}">
                        <i class="fa fa-th-large w-5 text-center text-lg"></i>
                        <span class="font-black tracking-wide">DASHBOARD</span>
                    </a>
                @endif

                @if (!$isSuperAdmin)
                <div class="pt-4 pb-2 px-4">
                    <span class="text-[11px] font-black text-slate-400 tracking-[0.3em] uppercase">Menu Utama</span>
                </div>

                <a href="{{ route('kasir.index') }}"
                    class="nav-item-transition flex items-center gap-3.5 rounded-xl px-4 py-3 {{ request()->routeIs('kasir.*') ? 'bg-sidebar-active text-white active-glow' : 'hover:bg-white/5 text-slate-200 hover:text-white' }}">
                    <i class="fa fa-desktop w-5 text-center text-lg"></i>
                    <span class="font-black tracking-wide">MENU PRODUK</span>
                </a>

                {{-- DATA MASTER --}}
                <div>
                    <button type="button" @click="openDropdown = openDropdown === 'master' ? null : 'master'"
                        class="nav-item-transition w-full flex items-center justify-between gap-3.5 rounded-xl px-4 py-3 hover:bg-white/5 {{ (request()->routeIs('menu.*', 'kategori.*', 'customer.*', 'users.*') && !request()->routeIs('menu.stok', 'menu.persediaan')) ? 'text-teal-400 bg-white/5' : 'text-slate-200 hover:text-white' }}">
                        <span class="flex items-center gap-3.5">
                            <i class="fa fa-database w-5 text-center text-lg"></i>
                            <span class="font-black tracking-wide">DATA MASTER</span>
                        </span>
                        <i class="fa fa-angle-right text-xs transition-transform duration-300" :class="openDropdown === 'master' && 'rotate-90'"></i>
                    </button>
                    <div x-show="openDropdown === 'master'" x-cloak x-collapse class="mt-1 space-y-1">
                        @if ($isAdmin)
                            <a href="{{ route('menu.index') }}" class="flex items-center gap-3 rounded-xl py-2.5 pl-12 pr-4 hover:bg-white/5 {{ request()->routeIs('menu.index', 'menu.tambah', 'menu.edit', 'menu.detail') ? 'text-teal-400 font-black' : 'text-slate-400 hover:text-slate-200' }}">
                                <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                <span class="text-base font-semibold">Produk / Menu</span>
                            </a>
                            <a href="{{ route('kategori.index') }}" class="flex items-center gap-3 rounded-xl py-2.5 pl-12 pr-4 hover:bg-white/5 {{ request()->routeIs('kategori.*') ? 'text-teal-400 font-black' : 'text-slate-400 hover:text-slate-200' }}">
                                <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                <span class="text-base font-semibold">Kategori</span>
                            </a>
                        @endif
                        <a href="{{ route('customer.index') }}" class="flex items-center gap-3 rounded-xl py-2.5 pl-12 pr-4 hover:bg-white/5 {{ request()->routeIs('customer.*') ? 'text-teal-400 font-black' : 'text-slate-400 hover:text-slate-200' }}">
                            <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                            <span class="text-base font-semibold">Kategori Customer</span>
                        </a>
                        @if ($isAdmin)
                            <a href="{{ route('users.index') }}" class="flex items-center gap-3 rounded-xl py-2.5 pl-12 pr-4 hover:bg-white/5 {{ request()->routeIs('users.*') ? 'text-teal-400 font-black' : 'text-slate-400 hover:text-slate-200' }}">
                                <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                <span class="text-base font-semibold">Manajemen User</span>
                            </a>
                        @endif
                    </div>
                </div>

                @if ($isAdmin)
                    {{-- STOK --}}
                    <div>
                        <button type="button" @click="openDropdown = openDropdown === 'stok' ? null : 'stok'"
                            class="nav-item-transition w-full flex items-center justify-between gap-3.5 rounded-xl px-4 py-3 hover:bg-white/5 {{ request()->routeIs('menu.stok', 'menu.persediaan') ? 'text-teal-400 bg-white/5' : 'text-slate-200 hover:text-white' }}">
                            <span class="flex items-center gap-3.5">
                                <i class="fa fa-archive w-5 text-center text-lg"></i>
                                <span class="font-black tracking-wide">STOK BARANG</span>
                            </span>
                            <i class="fa fa-angle-right text-xs transition-transform duration-300" :class="openDropdown === 'stok' && 'rotate-90'"></i>
                        </button>
                        <div x-show="openDropdown === 'stok'" x-cloak x-collapse class="mt-1 space-y-1">
                            <a href="{{ route('menu.stok') }}" class="flex items-center gap-3 rounded-xl py-2.5 pl-12 pr-4 hover:bg-white/5 {{ request()->routeIs('menu.stok') ? 'text-teal-400 font-black' : 'text-slate-400 hover:text-slate-200' }}">
                                <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                <span class="text-base font-semibold">Entry Stok</span>
                            </a>
                            <a href="{{ route('menu.persediaan') }}" class="flex items-center gap-3 rounded-xl py-2.5 pl-12 pr-4 hover:bg-white/5 {{ request()->routeIs('menu.persediaan') ? 'text-teal-400 font-black' : 'text-slate-400 hover:text-slate-200' }}">
                                <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                <span class="text-base font-semibold">Daftar Persediaan</span>
                            </a>
                        </div>
                    </div>
                @endif

                {{-- ORDER --}}
                <div>
                    <button type="button" @click="openDropdown = openDropdown === 'order' ? null : 'order'"
                        class="nav-item-transition w-full flex items-center justify-between gap-3.5 rounded-xl px-4 py-3 hover:bg-white/5 {{ request()->routeIs('order.*') ? 'text-teal-400 bg-white/5' : 'text-slate-200 hover:text-white' }}">
                        <span class="flex items-center gap-3.5">
                            <i class="fa fa-shopping-cart w-5 text-center text-lg"></i>
                            <span class="font-black tracking-wide">TRANSAKSI</span>
                            @if ($sidebarTotal > 0)
                                <span class="ml-1 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-[10px] font-black text-white shadow-lg shadow-red-500/30 animate-pulse">{{ $sidebarTotal }}</span>
                            @endif
                        </span>
                        <i class="fa fa-angle-right text-xs transition-transform duration-300" :class="openDropdown === 'order' && 'rotate-90'"></i>
                    </button>
                    <div x-show="openDropdown === 'order'" x-cloak x-collapse class="mt-1 space-y-1">
                        <a href="{{ route('order.index') }}" class="flex items-center justify-between rounded-xl py-2.5 pl-12 pr-4 hover:bg-white/5 {{ request()->routeIs('order.index') && !request()->has('jenis') ? 'text-teal-400 font-black' : 'text-slate-400 hover:text-slate-200' }}">
                            <span class="text-base font-semibold">Semua Order</span>
                            <span class="rounded-lg bg-slate-800 px-2 py-0.5 text-[10px] font-bold">{{ $sidebarTotal }}</span>
                        </a>
                        <a href="{{ route('order.index', ['jenis' => 1]) }}" class="flex items-center justify-between rounded-xl py-2.5 pl-12 pr-4 hover:bg-white/5 {{ request('jenis') == '1' ? 'text-teal-400 font-black' : 'text-slate-400 hover:text-slate-200' }}">
                            <span class="text-base font-semibold">Makan di Tempat</span>
                            <span class="rounded-lg bg-blue-500/20 text-blue-400 px-2 py-0.5 text-[10px] font-bold">{{ $sidebarDitempat }}</span>
                        </a>
                        <a href="{{ route('order.index', ['jenis' => 2]) }}" class="flex items-center justify-between rounded-xl py-2.5 pl-12 pr-4 hover:bg-white/5 {{ request('jenis') == '2' ? 'text-teal-400 font-black' : 'text-slate-400 hover:text-slate-200' }}">
                            <span class="text-base font-semibold">Booking</span>
                            <span class="rounded-lg bg-amber-500/20 text-amber-400 px-2 py-0.5 text-[10px] font-bold">{{ $sidebarBooking }}</span>
                        </a>
                    </div>
                </div>

                @if ($isAdmin)
                    <div class="pt-4 pb-2 px-4">
                        <span class="text-[11px] font-black text-slate-400 tracking-[0.3em] uppercase">Analitik & Laporan</span>
                    </div>

                    {{-- LAPORAN --}}
                    <div>
                        <button type="button" @click="openDropdown = openDropdown === 'laporan' ? null : 'laporan'"
                            class="nav-item-transition w-full flex items-center justify-between gap-3.5 rounded-xl px-4 py-3 hover:bg-white/5 {{ request()->routeIs('laporan.*') ? 'text-teal-400 bg-white/5' : 'text-slate-200 hover:text-white' }}">
                            <span class="flex items-center gap-3.5">
                                <i class="fa fa-bar-chart w-5 text-center text-lg"></i>
                                <span class="font-black tracking-wide">LAPORAN</span>
                            </span>
                            <i class="fa fa-angle-right text-xs transition-transform duration-300" :class="openDropdown === 'laporan' && 'rotate-90'"></i>
                        </button>
                        <div x-show="openDropdown === 'laporan'" x-cloak x-collapse class="mt-1 space-y-1">
                            <a href="{{ route('laporan.index') }}" class="flex items-center gap-3 rounded-xl py-2.5 pl-12 pr-4 hover:bg-white/5 {{ request()->routeIs('laporan.index') ? 'text-teal-400 font-black' : 'text-slate-400 hover:text-slate-200' }} text-base font-semibold">
                                <span class="h-1.5 w-1.5 rounded-full bg-current"></span> Penjualan Harian
                            </a>
                            <a href="{{ route('laporan.produk') }}" class="flex items-center gap-3 rounded-xl py-2.5 pl-12 pr-4 hover:bg-white/5 {{ request()->routeIs('laporan.produk') ? 'text-teal-400 font-black' : 'text-slate-400 hover:text-slate-200' }} text-base font-semibold">
                                <span class="h-1.5 w-1.5 rounded-full bg-current"></span> History Per Menu
                            </a>
                            <a href="{{ route('laporan.cash') }}" class="flex items-center gap-3 rounded-xl py-2.5 pl-12 pr-4 hover:bg-white/5 {{ request()->routeIs('laporan.cash') ? 'text-teal-400 font-black' : 'text-slate-400 hover:text-slate-200' }} text-base font-semibold">
                                <span class="h-1.5 w-1.5 rounded-full bg-current"></span> Aliran Kas (Cashflow)
                            </a>
                        </div>
                    </div>

                    {{-- APRIORI --}}
                    <div>
                        <button type="button" @click="openDropdown = openDropdown === 'apriori' ? null : 'apriori'"
                            class="nav-item-transition w-full flex items-center justify-between gap-3.5 rounded-xl px-4 py-3 hover:bg-white/5 {{ request()->routeIs('algoritma.*') ? 'text-teal-400 bg-white/5' : 'text-slate-200 hover:text-white' }}">
                            <span class="flex items-center gap-3.5">
                                <i class="fa fa-sitemap w-5 text-center text-lg"></i>
                                <span class="font-black tracking-wide">APRIORI</span>
                            </span>
                            <i class="fa fa-angle-right text-xs transition-transform duration-300" :class="openDropdown === 'apriori' && 'rotate-90'"></i>
                        </button>
                        <div x-show="openDropdown === 'apriori'" x-cloak x-collapse class="mt-1 space-y-1">
                            <a href="{{ route('algoritma.index') }}" class="flex items-center gap-3 rounded-xl py-2.5 pl-12 pr-4 hover:bg-white/5 {{ request()->routeIs('algoritma.index') ? 'text-teal-400 font-black' : 'text-slate-400 hover:text-slate-200' }} text-base font-semibold">
                                <span class="h-1.5 w-1.5 rounded-full bg-current"></span> Export Dataset
                            </a>
                            <a href="{{ route('algoritma.rule') }}" class="flex items-center gap-3 rounded-xl py-2.5 pl-12 pr-4 hover:bg-white/5 {{ request()->routeIs('algoritma.rule') ? 'text-teal-400 font-black' : 'text-slate-400 hover:text-slate-200' }} text-base font-semibold">
                                <span class="h-1.5 w-1.5 rounded-full bg-current"></span> Penentuan Rule
                            </a>
                            <a href="{{ route('algoritma.hasil') }}" class="flex items-center gap-3 rounded-xl py-2.5 pl-12 pr-4 hover:bg-white/5 {{ request()->routeIs('algoritma.hasil', 'algoritma.detail') ? 'text-teal-400 font-black' : 'text-slate-400 hover:text-slate-200' }} text-base font-semibold">
                                <span class="h-1.5 w-1.5 rounded-full bg-current"></span> Hasil Analisis
                            </a>
                        </div>
                    </div>
                @endif
                @endif
            </nav>

            {{-- User Info (Bottom Sidebar) --}}
            <div class="p-6 border-t border-white/5 bg-black/20">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-full bg-teal-500/10 border border-teal-500/20 flex items-center justify-center text-teal-400 font-bold shadow-inner">
                        {{ substr($userName, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-white truncate">{{ $userName }}</p>
                        <div class="flex items-center gap-1.5 mt-0.5">
                            <span class="text-[9px] font-bold bg-teal-600/30 text-teal-300 px-1.5 py-0.5 rounded uppercase tracking-wider">{{ auth()->user()->level ?? 'User' }}</span>
                            @if(auth()->user()->umkm && auth()->user()->umkm->kode_umkm)
                                <span class="text-[9px] font-mono font-bold text-slate-400 bg-slate-800 px-1.5 py-0.5 rounded tracking-wide">{{ auth()->user()->umkm->kode_umkm }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Main --}}
        <div class="flex-1 flex flex-col min-w-0">
            <header class="sticky top-0 z-30 flex items-center justify-between h-16 px-4 bg-white border-b border-slate-200 shadow-sm">
                <div class="flex items-center gap-3">
                    <button type="button" @click="sidebarOpen = true" class="lg:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100">
                        <i class="fa fa-bars text-lg"></i>
                    </button>
                    <h1 class="text-lg font-semibold text-slate-800 truncate">{{ $pageTitle }}</h1>
                </div>

                <div class="relative" @click.outside="userOpen = false">
                    <button type="button" @click="userOpen = !userOpen"
                        class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 transition">
                        <i class="fa fa-user-circle text-xl text-teal-600"></i>
                        <span class="hidden sm:inline max-w-[140px] truncate">{{ $userName }}</span>
                        <i class="fa fa-chevron-down text-xs text-slate-400"></i>
                    </button>
                    <div x-show="userOpen" x-cloak x-transition
                        class="absolute right-0 mt-2 w-52 rounded-xl bg-white shadow-lg ring-1 ring-slate-200 py-1 text-sm z-50">
                        @if ($isAdmin)
                            <a href="{{ route('info.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-slate-700 hover:bg-slate-50">
                                <i class="fa fa-cog text-slate-400 w-4"></i> Pengaturan Toko
                            </a>
                            <hr class="my-1 border-slate-100">
                        @endif
                        <a href="{{ route('user.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-slate-700 hover:bg-slate-50">
                            <i class="fa fa-edit text-slate-400 w-4"></i> Profil
                        </a>
                        <hr class="my-1 border-slate-100">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2 px-4 py-2.5 text-red-600 hover:bg-red-50 text-left">
                                <i class="fa fa-sign-out w-4"></i> Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <main class="flex-1 p-4 md:p-6">
                @if (session('success'))
                    <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                        {!! session('success') !!}
                    </div>
                @endif
                @if (session('failed') || session('error'))
                    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                        {!! session('failed') ?? session('error') !!}
                    </div>
                @endif
                @if (session('warning'))
                    <div class="mb-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                        {!! session('warning') !!}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js"></script>
    @stack('scripts')
</body>
</html>
