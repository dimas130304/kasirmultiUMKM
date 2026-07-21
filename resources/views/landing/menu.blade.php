<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title_web }} &mdash; KASIR MULTI-UMKM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');
        body {
            font-family: 'Outfit', sans-serif;
        }
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
<body class="bg-slate-50 text-slate-800 min-h-screen pb-24" x-data="menuApp()" x-init="initCart()">

    <!-- Header -->
    <header class="sticky top-0 z-45 bg-white/95 backdrop-blur-md border-b border-slate-100 px-4 py-4 flex items-center justify-between shadow-sm">
        <a href="{{ route('landing') }}" class="flex items-center gap-2 text-slate-600 hover:text-teal-600 transition font-bold text-sm">
            <i class="fa fa-arrow-left"></i>
            <span>Kembali</span>
        </a>
        <span class="text-slate-800 font-extrabold text-xs sm:text-sm uppercase tracking-widest">Menu Digital</span>
        <button @click="openCart = true" class="relative flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 hover:bg-slate-200 transition">
            <i class="fa fa-shopping-cart text-slate-700 text-lg"></i>
            <span x-show="totalCount > 0" class="absolute -top-1 -right-1 bg-red-500 text-white font-bold text-[9px] h-5 w-5 rounded-full flex items-center justify-center shadow-md animate-pulse" x-text="totalCount"></span>
        </button>
    </header>

    <!-- Store Banner & Info Section -->
    <div class="max-w-7xl mx-auto px-4 pt-6">
        {{-- Large Store Photo Banner --}}
        <div class="relative w-full h-56 sm:h-72 md:h-96 rounded-3xl overflow-hidden bg-slate-50 border border-slate-200/80 shadow-md group/banner">
            @if($umkm->store_photo)
                <img src="{{ asset('assets/image/user/' . $umkm->store_photo) }}" alt="{{ $umkm->nama_umkm }}" class="w-full h-full object-cover">
            @else
                {{-- Matching placeholder gradient --}}
                <div class="w-full h-full bg-gradient-to-br from-teal-50 to-emerald-50 flex flex-col items-center justify-center gap-3 text-teal-600">
                    <div class="h-16 w-16 rounded-full bg-white flex items-center justify-center text-teal-600 shadow-md border border-teal-50">
                        <i class="fa fa-shopping-bag text-2xl"></i>
                    </div>
                    <span class="text-xs text-teal-700/60 font-bold uppercase tracking-widest">KASIR MULTI-UMKM</span>
                </div>
            @endif
        </div>

        {{-- Store Info (Name and Address below the image) --}}
        <div class="text-center mt-6 mb-8">
            <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight">
                {{ $umkm->nama_umkm }}
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-2 flex items-center justify-center gap-1.5 max-w-2xl mx-auto">
                <i class="fa fa-map-marker text-teal-500 flex-shrink-0 text-base"></i> 
                <span>{{ $umkm->alamat_umkm }}</span>
            </p>
        </div>
    </div>

    <!-- Category Tabs -->
    <div class="sticky top-[72px] z-30 bg-slate-50 py-3 px-4 border-b border-slate-100 overflow-x-auto flex gap-2 no-scrollbar">
        <button @click="activeCategory = 'all'" :class="activeCategory === 'all' ? 'bg-teal-600 text-white shadow-md shadow-teal-600/20' : 'bg-white text-slate-600 border border-slate-200'" class="whitespace-nowrap rounded-xl px-4 py-2 text-xs font-bold transition-all duration-200">
            Semua Menu
        </button>
        @foreach($categories as $cat)
            <button @click="activeCategory = '{{ $cat->id }}'" :class="activeCategory === '{{ $cat->id }}' ? 'bg-teal-600 text-white shadow-md shadow-teal-600/20' : 'bg-white text-slate-600 border border-slate-200'" class="whitespace-nowrap rounded-xl px-4 py-2 text-xs font-bold transition-all duration-200">
                {{ $cat->kategori }}
            </button>
        @endforeach
    </div>

    <!-- Menu List -->
    <main class="px-4 py-6 max-w-7xl mx-auto pb-24">
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 md:gap-6">
            @forelse($menus as $menu)
                <div class="menu-item bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-lg hover:border-teal-300 transition group flex flex-col" data-kategori="{{ $menu->id_kategori }}" x-show="activeCategory === 'all' || activeCategory === '{{ $menu->id_kategori }}'">
                    <button type="button" class="w-full text-left flex-1 flex flex-col" @click="addTrigger({ id: {{ $menu->id }}, nama: '{{ addslashes($menu->nama) }}', harga: {{ $menu->harga_jual }}, stok: {{ $menu->stok }} })">
                        <div class="aspect-[4/3] w-full bg-slate-100 flex items-center justify-center overflow-hidden shrink-0 relative">
                            @if($menu->gambar && $menu->gambar !== '-' && $menu->gambar !== '')
                                <img src="{{ asset('assets/image/produk/'.$menu->gambar) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" onerror="this.onerror=null;this.src='{{ asset('assets/image/no_screenshot.png') }}';">
                            @else
                                <i class="fa fa-image text-4xl text-slate-300 group-hover:scale-110 transition duration-500"></i>
                            @endif
                            <div class="absolute inset-0 bg-teal-900/0 group-hover:bg-teal-900/10 transition duration-300"></div>
                        </div>
                        <div class="p-3 md:p-4 flex flex-col flex-1">
                            <p class="text-[10px] md:text-xs text-teal-600 font-bold tracking-wide uppercase mb-1">{{ $menu->kategori->kategori ?? 'Umum' }}</p>
                            <h3 class="font-bold text-slate-800 text-xs md:text-sm mb-1 line-clamp-2 leading-snug">{{ $menu->nama }}</h3>
                            <div class="mt-auto pt-2">
                                <span class="font-black text-emerald-600 text-sm md:text-base block mb-1">Rp {{ number_format($menu->harga_jual, 0, ',', '.') }}</span>
                                <span class="text-[10px] text-slate-500 font-medium bg-slate-100 px-2 py-0.5 rounded-full">Stok: {{ $menu->stok }}</span>
                            </div>
                        </div>
                    </button>
                    <div class="px-3 pb-3 md:px-4 md:pb-4 pt-0 mt-auto">
                        <button @click="addTrigger({ id: {{ $menu->id }}, nama: '{{ addslashes($menu->nama) }}', harga: {{ $menu->harga_jual }}, stok: {{ $menu->stok }} })" class="w-full bg-teal-50 hover:bg-teal-600 text-teal-700 hover:text-white border border-teal-200 hover:border-teal-600 text-xs font-bold py-2 rounded-lg transition active:scale-95 flex justify-center items-center gap-1.5 shadow-sm">
                            <i class="fa fa-plus"></i> Tambah
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-16 bg-white rounded-2xl border border-dashed border-slate-300">
                    <i class="fa fa-cutlery text-slate-300 text-5xl mb-4 block"></i>
                    <h3 class="font-bold text-slate-700 text-lg">Tidak Ada Menu</h3>
                    <p class="text-sm text-slate-500 mt-1">Belum ada menu yang terdaftar di toko ini.</p>
                </div>
            @endforelse
        </div>
    </main>

    <!-- Bottom Bar -->
    <div x-show="totalCount > 0" class="fixed bottom-0 left-0 right-0 z-40 bg-white border-t border-slate-150 px-4 py-3.5 shadow-2xl flex items-center justify-between" x-cloak>
        <div>
            <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">Total Pesanan</p>
            <p class="text-base font-extrabold text-teal-600" x-text="'Rp ' + formatRupiah(totalPrice)"></p>
        </div>
        <button @click="openCart = true" class="bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-teal-600/25 transition active:scale-95 flex items-center gap-2 text-xs">
            Lihat Keranjang <i class="fa fa-arrow-right"></i>
        </button>
    </div>

    <!-- Recommendations Modal -->
    <div x-show="showRecModal" class="fixed inset-0 z-[60] overflow-hidden flex items-end justify-center sm:items-center px-4" x-cloak>
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="showRecModal = false"></div>
        
        <div class="bg-white rounded-t-3xl sm:rounded-3xl w-full max-w-md p-0 shadow-2xl relative z-10 transition-transform duration-300 transform translate-y-0 max-h-[90vh] overflow-hidden flex flex-col">
            {{-- Big Product Banner at the Top --}}
            <div class="relative w-full h-52 bg-slate-100 shrink-0">
                <template x-if="recTriggerGambar && recTriggerGambar !== '-' && recTriggerGambar !== ''">
                    <img :src="'{{ asset('assets/image/produk') }}/' + recTriggerGambar" class="w-full h-full object-cover">
                </template>
                <template x-if="!recTriggerGambar || recTriggerGambar === '-' || recTriggerGambar === ''">
                    <div class="w-full h-full bg-gradient-to-br from-teal-50 to-emerald-50 flex items-center justify-center text-teal-600">
                        <i class="fa fa-image text-5xl opacity-40"></i>
                    </div>
                </template>
                {{-- Close Button --}}
                <button @click="showRecModal = false" class="absolute top-4 right-4 h-8 w-8 rounded-full bg-black/40 text-white flex items-center justify-center hover:bg-black/60 transition shadow">
                    <i class="fa fa-times text-base"></i>
                </button>
            </div>

            {{-- Product Info Section --}}
            <div class="px-5 py-4 border-b border-slate-100 bg-white">
                <span class="bg-teal-50 text-teal-700 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider mb-1 inline-block">Produk dipilih</span>
                <h3 class="font-extrabold text-xl text-slate-900 leading-tight" x-text="recTriggerItem"></h3>
            </div>

            <div class="p-5 overflow-y-auto custom-scrollbar bg-slate-50/50">
                <div class="mb-2">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">
                        <i class="fa fa-lightbulb-o text-amber-500 mr-1 text-base"></i>
                        Pembeli lain biasanya juga membeli:
                    </p>
                </div>

                <div class="space-y-3 mb-2">
                    <template x-for="rec in recommendations" :key="rec.produk">
                        <div class="flex items-center justify-between bg-amber-50 border border-amber-200 rounded-2xl p-3 hover:bg-amber-100 transition cursor-pointer group shadow-sm" @click="addRecItemAndPending(rec)">
                            <div class="flex items-center gap-3.5 min-w-0 pr-2">
                                {{-- Gambar Produk Rekomendasi --}}
                                <div class="w-20 h-20 rounded-2xl overflow-hidden bg-white border border-amber-200 shrink-0 flex items-center justify-center relative shadow-sm">
                                    <template x-if="rec.gambar && rec.gambar !== '-' && rec.gambar !== ''">
                                        <img :src="'{{ asset('assets/image/produk') }}/' + rec.gambar" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!rec.gambar || rec.gambar === '-' || rec.gambar === ''">
                                        <i class="fa fa-image text-slate-350 text-2xl"></i>
                                    </template>
                                    <div class="absolute inset-0 bg-black/5 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                        <i class="fa fa-plus text-white text-xs"></i>
                                    </div>
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-slate-800 text-sm truncate" x-text="rec.produk"></p>
                                    <p class="text-[10px] font-medium text-amber-600 truncate uppercase tracking-wide mt-0.5" x-text="'Kecocokan: ' + rec.confidence + '%'"></p>
                                </div>
                            </div>
                            <span class="text-xs text-teal-700 bg-white border border-teal-200 font-bold px-3 py-1.5 rounded-lg group-hover:bg-teal-600 group-hover:text-white group-hover:border-teal-600 transition whitespace-nowrap ml-2 shadow-sm">Tambah</span>
                        </div>
                    </template>
                </div>
            </div>

            <div class="px-5 pb-5 pt-4 border-t border-slate-100 flex gap-3 shrink-0 bg-white shadow-[0_-10px_20px_rgba(0,0,0,0.02)]">
                <button @click="addPendingItem()" class="flex-1 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 text-sm transition shadow-lg shadow-teal-600/20 active:scale-95 flex items-center justify-center gap-2">
                    <i class="fa fa-cart-plus"></i> Tambah ke Keranjang
                </button>
                <button @click="showRecModal = false" class="px-5 py-3 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 transition font-medium text-sm">
                    Batal
                </button>
            </div>
        </div>
    </div>

    <!-- Cart Modal -->
    <div x-show="openCart" class="fixed inset-0 z-50 overflow-hidden flex items-end justify-center" x-cloak>
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" @click="openCart = false"></div>
        
        <div class="bg-white rounded-t-3xl max-w-sm w-full p-6 shadow-2xl relative z-10 flex flex-col max-h-[90vh]">
            <div class="flex justify-between items-center pb-4 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wide">Keranjang Belanja</h3>
                <button @click="openCart = false" class="text-slate-400 hover:text-slate-600 text-lg"><i class="fa fa-times"></i></button>
            </div>

            <div class="flex-1 overflow-y-auto py-4 space-y-3.5 pr-1">
                <template x-for="item in cart" :key="item.id">
                    <div class="flex justify-between items-start gap-3">
                        <div class="flex-1 min-w-0">
                            <span class="font-bold text-slate-800 text-xs block truncate" x-text="item.nama"></span>
                            <span class="text-[10px] text-slate-500 block mt-0.5" x-text="'Rp ' + formatRupiah(item.harga)"></span >
                            <input type="text" placeholder="Tambahkan catatan (opsional)..." 
                                   x-model="item.keterangan" 
                                   @change="saveCartToStorage()"
                                   class="mt-1 w-full bg-slate-50 border border-slate-200 rounded-lg px-2 py-1 text-[9px] text-slate-600 focus:outline-none focus:border-teal-500 transition">
                        </div>
                        <div class="flex items-center gap-2 bg-slate-100 rounded-xl p-1 flex-shrink-0">
                            <button @click="decreaseQty(item)" class="h-6 w-6 rounded-lg bg-white flex items-center justify-center text-slate-600 hover:bg-slate-50 active:scale-95 transition text-[10px]"><i class="fa fa-minus"></i></button>
                            <span class="font-bold text-slate-700 text-xs px-1" x-text="item.qty"></span>
                            <button @click="increaseQty(item)" class="h-6 w-6 rounded-lg bg-white flex items-center justify-center text-slate-600 hover:bg-slate-50 active:scale-95 transition text-[10px]"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                </template>
                <div x-show="cart.length === 0" class="text-center py-12 text-slate-400 text-xs">
                    Keranjang kosong
                </div>
            </div>

            <!-- Total info -->
            <div class="border-t border-slate-100 pt-3 space-y-1.5 text-xs text-slate-600" x-show="cart.length > 0">
                <div class="flex justify-between">
                    <span>Subtotal</span>
                    <span x-text="'Rp ' + formatRupiah(totalPrice)"></span>
                </div>
                @if($profil && $profil->diskon > 0)
                    <div class="flex justify-between text-green-600 font-semibold">
                        <span>Diskon ({{ $profil->diskon }}%)</span>
                        <span x-text="'-Rp ' + formatRupiah((totalPrice * {{ $profil->diskon }}) / 100)"></span>
                    </div>
                @endif
                @if($profil && $profil->pajak > 0)
                    <div class="flex justify-between text-slate-500">
                        <span>Pajak ({{ $profil->pajak }}%)</span>
                        <span x-text="'+Rp ' + formatRupiah(((totalPrice - (totalPrice * {{ $profil->diskon ?? 0 }}) / 100) * {{ $profil->pajak }}) / 100)"></span>
                    </div>
                @endif
                <div class="flex justify-between font-extrabold text-slate-800 text-sm border-t border-slate-150/50 pt-2 mt-1">
                    <span>Total Bayar</span>
                    <span class="text-teal-600" x-text="'Rp ' + formatRupiah(grandTotal)"></span>
                </div>
            </div>

            <div class="pt-4" x-show="cart.length > 0">
                <button @click="openCheckoutModal = true; openCart = false;" class="w-full text-center rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 text-xs shadow-lg shadow-teal-600/20 active:scale-95 transition flex items-center justify-center gap-2">
                    <i class="fa fa-check-square-o"></i> Lanjutkan Pemesanan
                </button>
            </div>
        </div>
    </div>

    <!-- Checkout Modal -->
    <div x-show="openCheckoutModal" class="fixed inset-0 z-50 overflow-hidden flex items-end justify-center px-4" x-cloak>
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" @click="openCheckoutModal = false"></div>
        
        <div class="bg-white rounded-t-3xl max-w-sm w-full p-6 shadow-2xl relative z-10 transition-transform duration-300 transform translate-y-0 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center pb-4 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wide">Data Pemesanan</h3>
                <button @click="openCheckoutModal = false" class="text-slate-400 hover:text-slate-600 text-lg"><i class="fa fa-times"></i></button>
            </div>

            <form @submit.prevent="submitOrder" class="mt-4 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Atas Nama Pemesan <span class="text-red-500">*</span></label>
                    <input type="text" required x-model="checkoutForm.atas_nama" placeholder="Masukkan nama Anda" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-xs outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500/20 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Tipe Pemesanan <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 gap-3">
                        <button type="button" @click="checkoutForm.pesanan = 'dine-in'" :class="checkoutForm.pesanan === 'dine-in' ? 'bg-teal-50 border-2 border-teal-600 text-teal-700' : 'bg-white border border-slate-200 text-slate-600'" class="rounded-xl p-3 flex flex-col items-center justify-center gap-1.5 font-bold text-xs transition active:scale-95">
                            <i class="fa fa-cutlery text-base"></i> Makan di Tempat
                        </button>
                        <button type="button" @click="checkoutForm.pesanan = 'takeaway'" :class="checkoutForm.pesanan === 'takeaway' ? 'bg-teal-50 border-2 border-teal-600 text-teal-700' : 'bg-white border border-slate-200 text-slate-600'" class="rounded-xl p-3 flex flex-col items-center justify-center gap-1.5 font-bold text-xs transition active:scale-95">
                            <i class="fa fa-shopping-basket text-base"></i> Bawa Pulang
                        </button>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" :disabled="submitting" class="w-full text-center rounded-xl bg-teal-600 hover:bg-teal-700 disabled:bg-slate-400 text-white font-bold py-3 text-xs shadow-lg shadow-teal-600/20 active:scale-95 transition flex items-center justify-center gap-2">
                        <i class="fa fa-rocket" x-show="!submitting"></i>
                        <span x-text="submitting ? 'Mengirim...' : 'Kirim Pesanan Sekarang'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script Application Logic -->
    <script>
        function menuApp() {
            return {
                activeCategory: 'all',
                openCart: false,
                openCheckoutModal: false,
                showRecModal: false,
                recTriggerItem: '',
                recTriggerGambar: '',
                recommendations: [],
                pendingProduct: null,
                submitting: false,
                
                cart: [],
                totalCount: 0,
                totalPrice: 0,
                grandTotal: 0,
                
                checkoutForm: {
                    atas_nama: '',
                    pesanan: 'dine-in',
                },

                initCart() {
                    const saved = localStorage.getItem('wpf_cart_{{ $umkm->kode_umkm }}');
                    if (saved) {
                        try {
                            this.cart = JSON.parse(saved);
                            this.calculateTotals();
                        } catch (e) {
                            this.cart = [];
                        }
                    }
                },

                saveCartToStorage() {
                    localStorage.setItem('wpf_cart_{{ $umkm->kode_umkm }}', JSON.stringify(this.cart));
                },

                calculateTotals() {
                    let count = 0;
                    let price = 0;
                    this.cart.forEach(item => {
                        count += item.qty;
                        price += item.harga * item.qty;
                    });
                    this.totalCount = count;
                    this.totalPrice = price;

                    let discPercent = {{ $profil->diskon ?? 0 }};
                    let taxPercent = {{ $profil->pajak ?? 0 }};
                    let discounted = price - (price * discPercent / 100);
                    let taxed = discounted + (discounted * taxPercent / 100);
                    this.grandTotal = Math.round(taxed);
                },

                addToCart(product) {
                    let existing = this.cart.find(item => item.id === product.id);
                    if (existing) {
                        if (existing.qty < product.stok) {
                            existing.qty++;
                        } else {
                            alert('Batas maksimal stok menu tercapai!');
                            return;
                        }
                    } else {
                        this.cart.push({
                            id: product.id,
                            nama: product.nama,
                            harga: product.harga,
                            stok: product.stok,
                            qty: 1,
                            keterangan: ''
                        });
                    }
                    this.calculateTotals();
                    this.saveCartToStorage();
                },

                addTrigger(product) {
                    if (product.stok <= 0) {
                        alert('Stok menu habis!');
                        return;
                    }
                    this.pendingProduct = product;
                    this.fetchRecommendationsForModal(product.nama);
                },

                fetchRecommendationsForModal(productName) {
                    const url = '{{ route("algoritma.rekomendasi") }}?nama=' + encodeURIComponent(productName) + '&umkm_id={{ $umkm->id }}';
                    fetch(url)
                        .then(res => res.json())
                        .then(data => {
                            if (data.rekomendasi && data.rekomendasi.length > 0) {
                                this.recTriggerItem = productName;
                                this.recTriggerGambar = data.main_gambar;
                                this.recommendations = data.rekomendasi;
                                this.showRecModal = true;
                            } else {
                                this.addPendingItem();
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            this.addPendingItem();
                        });
                },

                addPendingItem() {
                    if (this.pendingProduct) {
                        this.addToCart(this.pendingProduct);
                        this.pendingProduct = null;
                        
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Ditambahkan ke keranjang', timer: 1200, showConfirmButton: false });
                        }
                    }
                    this.showRecModal = false;
                },

                addRecItemAndPending(rec) {
                    // Add main product first
                    if (this.pendingProduct) {
                        this.addToCart(this.pendingProduct);
                        this.pendingProduct = null;
                    }
                    
                    // Add recommended product
                    let matchingMenu = null;
                    @foreach($menus as $menu)
                        if ('{{ addslashes($menu->nama) }}'.toLowerCase() === rec.produk.toLowerCase()) {
                            matchingMenu = {
                                id: {{ $menu->id }},
                                nama: '{{ addslashes($menu->nama) }}',
                                harga: {{ $menu->harga_jual }},
                                stok: {{ $menu->stok }}
                            };
                        }
                    @endforeach

                    if (matchingMenu && matchingMenu.stok > 0) {
                        this.addToCart(matchingMenu);
                        this.showRecModal = false;
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Produk utama & rekomendasi ditambahkan', timer: 1500, showConfirmButton: false });
                        }
                        this.openCart = true;
                    } else {
                        alert('Menu rekomendasi sedang habis atau tidak tersedia!');
                        this.showRecModal = false;
                    }
                },

                increaseQty(item) {
                    if (item.qty < item.stok) {
                        item.qty++;
                        this.calculateTotals();
                        this.saveCartToStorage();
                    } else {
                        alert('Stok menu tidak mencukupi!');
                    }
                },

                decreaseQty(item) {
                    item.qty--;
                    if (item.qty <= 0) {
                        this.cart = this.cart.filter(i => i.id !== item.id);
                    }
                    this.calculateTotals();
                    this.saveCartToStorage();
                },

                formatRupiah(num) {
                    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                },

                submitOrder() {
                    if (this.submitting) return;
                    this.submitting = true;

                    let payload = {
                        atas_nama: this.checkoutForm.atas_nama,
                        pesanan: this.checkoutForm.pesanan,
                        cart: this.cart.map(item => ({
                            id_menu: item.id,
                            qty: item.qty,
                            keterangan: item.keterangan
                        }))
                    };

                    fetch('{{ route("landing.place-order", $umkm->kode_umkm) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(payload)
                    })
                    .then(async res => {
                        if (!res.ok) {
                            const text = await res.text();
                            throw new Error('HTTP ' + res.status + ': ' + text.substring(0, 500));
                        }
                        return res.json();
                    })
                    .then(data => {
                        this.submitting = false;
                        if (data.error) {
                            alert(data.error);
                        } else if (data.redirect_url) {
                            this.cart = [];
                            localStorage.removeItem('wpf_cart_{{ $umkm->kode_umkm }}');
                            this.calculateTotals();
                            window.location.href = data.redirect_url;
                        } else {
                            alert(JSON.stringify(data));
                        }
                    })
                    .catch(err => {
                        this.submitting = false;
                        console.error(err);
                        alert('Error: ' + err.message);
                    });
                }
            };
        }
    </script>
</body>
</html>
