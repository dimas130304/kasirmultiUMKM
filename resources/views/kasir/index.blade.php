@extends('layouts.app')

@section('content')
<div class="relative grid grid-cols-1 xl:grid-cols-3 gap-6" x-data="{ showCart: false }">
    {{-- Main Content: Product Grid --}}
    <div class="xl:col-span-2 space-y-4">
        <div class="rounded-2xl bg-white shadow-sm border border-slate-100 overflow-hidden">
            <div class="bg-gradient-to-r from-teal-600 to-teal-500 px-5 py-3 text-white font-semibold flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="fa fa-cubes"></i> Daftar Produk
                </div>
                {{-- Mobile Cart Toggle Button --}}
                <button @click="showCart = true" class="xl:hidden flex items-center gap-2 bg-white/20 hover:bg-white/30 px-3 py-1.5 rounded-lg text-xs transition">
                    <i class="fa fa-shopping-cart"></i> Keranjang
                </button>
            </div>
            <div class="p-4">
                <form method="GET" class="flex flex-wrap gap-2 mb-4">
                    <select name="id" class="rounded-lg border border-slate-300 px-3 py-2 text-sm flex-1 min-w-[140px]" onchange="this.form.submit()">
                        <option value="">Semua Kategori</option>
                        @foreach ($kat as $k)
                            <option value="{{ $k->id }}" {{ (string)$filterId === (string)$k->id ? 'selected' : '' }}>{{ $k->kategori }}</option>
                        @endforeach
                    </select>
                    <div class="flex flex-1 min-w-[200px] gap-2">
                        <input type="text" name="cari" value="{{ $filterCari }}" placeholder="Cari produk..." class="rounded-lg border border-slate-300 px-3 py-2 text-sm flex-1">
                        <button type="submit" class="rounded-lg bg-teal-600 text-white px-4 py-2 text-sm"><i class="fa fa-search"></i></button>
                        <a href="{{ route('kasir.index') }}" class="rounded-lg bg-slate-200 text-slate-700 px-4 py-2 text-sm flex items-center"><i class="fa fa-refresh"></i></a>
                    </div>
                </form>
                
                <div id="load-data" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 md:gap-4"></div>
                <div id="loading" class="text-center py-12 text-slate-400"></div>
                <div class="flex justify-center gap-2 mt-6" id="pagination-wrap"></div>
            </div>
        </div>
    </div>

    {{-- Sidebar: Cart (Hidden on mobile by default, shown via overlay) --}}
    <div :class="showCart ? 'fixed inset-0 z-[60] flex justify-end bg-slate-900/60 p-4 lg:p-6 backdrop-blur-sm' : 'hidden xl:block'">
        <div x-show="showCart" @click.away="showCart = false" class="xl:hidden absolute inset-0"></div>
        
        <div class="relative w-full max-w-md bg-white rounded-3xl shadow-2xl xl:shadow-sm border border-slate-100 overflow-hidden flex flex-col max-h-full xl:max-h-none xl:sticky xl:top-20">
            <div class="bg-gradient-to-r from-slate-800 to-slate-700 px-6 py-4 text-white font-semibold flex items-center justify-between shrink-0">
                <div class="flex items-center gap-2">
                    <i class="fa fa-shopping-cart"></i> Keranjang Pesanan
                </div>
                <button @click="showCart = false" class="xl:hidden text-white/60 hover:text-white">
                    <i class="fa fa-times text-xl"></i>
                </button>
            </div>
            
            <form id="AddKasir" class="p-5 space-y-4 overflow-y-auto flex-1 custom-scrollbar">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">No. Bon</label>
                        <input type="text" readonly name="no_bon" id="no_bon" value="{{ $no_bon }}" class="w-full rounded-xl border border-slate-100 bg-slate-50 px-3 py-2 text-sm font-mono text-slate-600">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Customer</label>
                        <select name="customer_id" id="customer_id" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm outline-none focus:border-teal-500 transition">
                            <option value="0">Umum</option>
                            @foreach ($customers as $c)
                                <option value="{{ $c->id }}">{{ $c->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Atas Nama</label>
                    <input type="text" required name="atas_nama" id="atas_nama" placeholder="Nama pelanggan..." class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm outline-none focus:border-teal-500 transition">
                </div>

                {{-- Cart Items Area --}}
                <div class="space-y-2 py-2">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Daftar Item</label>
                    <div id="cart_keranjang" class="rounded-2xl border border-dashed border-slate-200 bg-slate-50/50 min-h-[120px] p-2 transition-all">
                        <div class="flex flex-col items-center justify-center h-full py-8 text-slate-400">
                            <i class="fa fa-shopping-basket text-2xl mb-2"></i>
                            <p class="text-xs italic">Keranjang masih kosong</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 pt-2">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Status Bayar</label>
                        <select name="status" id="status" required class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm outline-none focus:border-teal-500 transition">
                            <option value="">— Pilih —</option>
                            <option>Lunas</option>
                            <option>Bayar Nanti</option>
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tipe Order</label>
                        <select name="pesanan" id="pesanan" required class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm outline-none focus:border-teal-500 transition">
                            <option>Ditempat</option>
                            <option>Booking</option>
                        </select>
                    </div>
                </div>

                {{-- Booking Details Area --}}
                <div id="booking-fields" class="hidden space-y-3 p-4 bg-orange-50 rounded-2xl border border-orange-100 animate-fade-in">
                    <div class="flex items-center gap-2 mb-1">
                        <i class="fa fa-calendar-check-o text-orange-500"></i>
                        <span class="text-xs font-bold text-orange-700 uppercase">Detail Booking</span>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-orange-400 uppercase tracking-wider">Tanggal</label>
                            <input type="date" name="booking_date" id="booking_date" min="{{ date('Y-m-d') }}" class="w-full rounded-xl border border-orange-200 px-3 py-2 text-sm outline-none focus:border-orange-500 transition">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-orange-400 uppercase tracking-wider">Jam</label>
                            <select name="booking_time" id="booking_time" class="w-full rounded-xl border border-orange-200 px-3 py-2 text-sm outline-none focus:border-orange-500 transition">
                                <option value="">Pilih Jam</option>
                                @for ($i = 9; $i <= 21; $i++)
                                    @php $h = str_pad($i, 2, '0', STR_PAD_LEFT); @endphp
                                    <option value="{{ $h }}:00">{{ $h }}:00</option>
                                    <option value="{{ $h }}:30">{{ $h }}:30</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <p class="text-[10px] text-orange-600 italic">* Booking akan dikonfirmasi via WhatsApp</p>
                </div>

                <input type="hidden" name="diskon" id="Diskon" value="0">
                <input type="hidden" name="pajak" id="Pajak" value="0">
                <input type="hidden" name="voucher" id="voucher" value="0">

                <div class="space-y-3 pt-4 border-t border-slate-100">
                    <input type="hidden" id="totalBayar" value="0">
                    <div class="flex justify-between items-center bg-teal-50 p-3 rounded-2xl border border-teal-100">
                        <span class="text-sm font-bold text-teal-800">Grand Total</span>
                        <input type="text" readonly name="grandtotal" id="GrandTotal" value="0" class="text-right font-black text-teal-700 bg-transparent border-none p-0 w-32 outline-none pointer-events-none text-lg">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Nominal Bayar</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-slate-400 font-bold text-sm">Rp</span>
                            <input type="number" name="dibayar" id="dibayar" value="0" class="w-full rounded-xl border border-slate-200 pl-10 pr-4 py-2.5 text-sm font-black text-slate-800 outline-none focus:ring-4 focus:ring-teal-500/10 transition">
                        </div>
                    </div>
                    <div class="flex justify-between items-center px-1">
                        <span class="text-xs font-bold text-slate-500" id="label-kembalian">Kembalian</span>
                        <input type="text" readonly id="kembalian" value="0" class="text-right font-black text-slate-700 bg-transparent border-none p-0 w-32 outline-none pointer-events-none">
                    </div>
                </div>

                <button type="submit" id="prosesTransaksi" class="w-full rounded-2xl bg-teal-600 hover:bg-teal-700 text-white font-black py-4 shadow-xl shadow-teal-600/30 transition transform active:scale-95 flex items-center justify-center gap-3">
                    <i class="fa fa-check-circle text-lg"></i> SIMPAN TRANSAKSI
                </button>
            </form>
        </div>
    </div>

    {{-- Mobile Floating Cart Button (Alternate) --}}
    <button @click="showCart = true" class="xl:hidden fixed bottom-6 right-6 z-50 h-14 w-14 rounded-full bg-teal-600 text-white shadow-2xl shadow-teal-600/40 flex items-center justify-center animate-bounce">
        <div class="relative">
            <i class="fa fa-shopping-cart text-xl"></i>
            <span id="cart-badge" class="absolute -top-2 -right-2 bg-red-500 text-white text-[10px] font-bold h-5 w-5 rounded-full flex items-center justify-center border-2 border-white hidden">0</span>
        </div>
    </button>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const menuUrl = {!! json_encode(route('kasir.dtmenu', array_filter(['id' => $filterId, 'cari' => $filterCari]))) !!};
const totalPages = {{ $pages }};
let currentPage = 1;

function loadData(page) {
    currentPage = page;
    $('#loading').html('<i class="fa fa-spinner fa-spin text-2xl text-teal-600"></i>');
    $.post(menuUrl, { pageHome: page, _token: '{{ csrf_token() }}' }, function (html) {
        $('#loading').html('');
        $('#load-data').html(html);
        bindPagination();
    });
}

function bindPagination() {
    let html = '';
    for (let i = 1; i <= totalPages; i++) {
        html += `<button type="button" data-page="${i}" class="px-3 py-1 rounded-lg text-sm ${i === currentPage ? 'bg-teal-600 text-white' : 'bg-slate-200 text-slate-700'}">${i}</button>`;
    }
    $('#pagination-wrap').html(html);
    $('#pagination-wrap button').on('click', function () { loadData($(this).data('page')); });
}

// ============================================================
// FUNGSI UTAMA KERANJANG
// ============================================================

function reloadCart() {
    fetch('{{ route('kasir.cart') }}')
        .then(res => res.text())
        .then(html => {
            document.getElementById('cart_keranjang').innerHTML = html;
            
            // Ambil total dari input hidden di partial
            const rawTotalEl = document.getElementById('raw_total');
            const totalBayarEl = document.getElementById('totalBayar');
            
            if (totalBayarEl) {
                const total = rawTotalEl ? (parseInt(rawTotalEl.value) || 0) : 0;
                totalBayarEl.dataset.raw = total; // Simpan angka mentah di dataset
                hitungGrandTotal(); // Update tampilan format
            }

            // Update badge pada tombol mobile
            const itemCount = document.querySelectorAll('#cart_keranjang .cart-item').length;
            const badge = document.getElementById('cart-badge');
            if (badge) {
                if (itemCount > 0) {
                    badge.textContent = itemCount;
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            }
        })
        .catch(err => console.error('Gagal reload cart:', err));
}

// Hapus 1 item dari keranjang
function hapusCartItem(idMenu) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    fetch('{{ route('kasir.del-cart') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'id_menu=' + idMenu,
    }).then(() => {
        reloadCart();
    });
}

// Update qty item di keranjang
function updateQty(idMenu, type) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    fetch('{{ route('kasir.update-cart') }}?id=' + idMenu, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'type=' + type + '&qt=1',
    }).then(() => {
        reloadCart();
    });
}

function hitungGrandTotal() {
    const totalBayarEl = document.getElementById('totalBayar');
    const grandTotalEl = document.getElementById('GrandTotal');
    const items = document.querySelectorAll('#cart_keranjang .cart-item');
    
    // Validasi awal: jika tidak ada item, total harus 0
    let t = 0;
    if (items.length > 0) {
        // Ambil nilai dari dataset.raw (angka mentah) atau fallback ke value (yang mungkin terformat)
        const rawValue = totalBayarEl?.dataset.raw;
        if (rawValue !== undefined && rawValue !== null && rawValue !== '') {
            t = parseInt(rawValue, 10) || 0;
        } else {
            // Jika terpaksa ambil dari value, hilangkan karakter non-digit (titik pemisah ribuan)
            const val = totalBayarEl?.value || '0';
            t = parseInt(val.replace(/[^0-9]/g, ''), 10) || 0;
        }
    }
    
    if (totalBayarEl) {
        totalBayarEl.dataset.raw = t;
        totalBayarEl.value = new Intl.NumberFormat('id-ID').format(t);
    }
    
    if (grandTotalEl) {
        grandTotalEl.value = new Intl.NumberFormat('id-ID').format(t);
    }
    
    hitungKembalian();
}

function hitungKembalian() {
    const grandTotalEl = document.getElementById('GrandTotal');
    const dibayarEl = document.getElementById('dibayar');
    const kembalianEl = document.getElementById('kembalian');
    const labelKembalian = document.getElementById('label-kembalian');

    const total = parseInt((grandTotalEl?.value || '0').replace(/[^0-9]/g, ''), 10);
    const bayar = parseInt(dibayarEl?.value || 0, 10);
    const kembali = bayar - total;
    
    if (kembalianEl) {
        kembalianEl.value = new Intl.NumberFormat('id-ID').format(Math.abs(kembali));
        
        if (bayar > 0 && kembali < 0) {
            // Nominal Kurang
            kembalianEl.classList.remove('text-slate-700', 'text-emerald-700');
            kembalianEl.classList.add('text-red-600');
            labelKembalian.textContent = 'Kurang';
            labelKembalian.classList.remove('text-slate-500');
            labelKembalian.classList.add('text-red-500');
        } else if (kembali > 0) {
            // Ada Kembalian
            kembalianEl.classList.remove('text-slate-700', 'text-red-600');
            kembalianEl.classList.add('text-emerald-700');
            labelKembalian.textContent = 'Kembalian';
            labelKembalian.classList.remove('text-red-500');
            labelKembalian.classList.add('text-slate-500');
        } else {
            // Pas atau Belum Bayar
            kembalianEl.classList.remove('text-red-600', 'text-emerald-700');
            kembalianEl.classList.add('text-slate-700');
            labelKembalian.textContent = 'Kembalian';
            labelKembalian.classList.remove('text-red-500');
            labelKembalian.classList.add('text-slate-500');
        }
    }
}

// Expose globally (dipakai oleh partial)
window.reloadCart    = reloadCart;
window.hapusCartItem = hapusCartItem;
window.updateQty     = updateQty;
window.hitungGrandTotal = hitungGrandTotal;

// ============================================================
// LOGIKA REKOMENDASI & MODAL
// ============================================================

let _currentProdukId = null;

// Gunakan delegasi event agar bekerja untuk elemen yang di-load via AJAX
$(document).on('click', '.pilih', function() {
    const id = $(this).data('id');
    const nama = $(this).data('nama');
    const harga = parseInt($(this).data('harga'));
    const gambar = $(this).data('gambar');

    _currentProdukId = id;

    // Isi info produk di modal
    $('#rekNamaProduk').text(nama);
    $('#rekHargaProduk').text('Rp ' + harga.toLocaleString('id-ID'));

    // Set banner image
    if (gambar && gambar !== '-' && gambar !== '') {
        $('#rekBannerImg').attr('src', '{{ asset("assets/image/produk") }}/' + gambar).removeClass('hidden');
        $('#rekBannerPlaceholder').addClass('hidden');
    } else {
        $('#rekBannerImg').addClass('hidden');
        $('#rekBannerPlaceholder').removeClass('hidden');
    }

    // Reset area
    $('#rekArea').addClass('hidden');
    $('#rekKosong').addClass('hidden');
    $('#rekLoadingArea').removeClass('hidden');
    $('#modalRekomendasi').removeClass('hidden');

    // Fetch rekomendasi
    fetch('{{ route("algoritma.rekomendasi") }}?nama=' + encodeURIComponent(nama) + '&umkm_id={{ Auth::user()->umkm_id }}')
        .then(res => res.json())
        .then(data => {
            $('#rekLoadingArea').addClass('hidden');

            if (data.rekomendasi && data.rekomendasi.length > 0) {
                let html = '';
                data.rekomendasi.forEach(function(r) {
                    let imgHtml = '';
                    if (r.gambar && r.gambar !== '-' && r.gambar !== '') {
                        imgHtml = `<img src="{{ asset('assets/image/produk') }}/${r.gambar}" class="w-full h-full object-cover">`;
                    } else {
                        imgHtml = `<i class="fa fa-image text-slate-350 text-2xl"></i>`;
                    }
                    html += `
                    <div class="flex items-center justify-between bg-amber-50 border border-amber-200 rounded-2xl p-3 hover:bg-amber-100 transition cursor-pointer group shadow-sm"
                        onclick="tambahRekomendasiDanLanjut('${r.produk.replace(/'/g, "\\'")}')">
                        <div class="flex items-center gap-3.5 min-w-0 pr-2">
                            {{-- Gambar Produk Rekomendasi --}}
                            <div class="w-20 h-20 rounded-2xl overflow-hidden bg-white border border-amber-200 shrink-0 flex items-center justify-center relative shadow-sm">
                                ${imgHtml}
                                <div class="absolute inset-0 bg-black/5 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                    <i class="fa fa-plus text-white text-xs"></i>
                                </div>
                            </div>
                            <div class="min-w-0">
                                <p class="font-bold text-slate-800 text-sm truncate">${r.produk}</p>
                                <p class="text-[10px] font-medium text-amber-600 truncate uppercase tracking-wide mt-0.5">Kecocokan: ${r.confidence}%</p>
                            </div>
                        </div>
                        <span class="text-xs text-teal-700 bg-white border border-teal-200 font-bold px-3 py-1.5 rounded-lg group-hover:bg-teal-600 group-hover:text-white group-hover:border-teal-600 transition whitespace-nowrap ml-2 shadow-sm">Tambah</span>
                    </div>`;
                });
                $('#rekList').html(html);

                if (data.info) {
                    $('#rekInfoBulan').text('Berdasarkan data bulan ' + data.info.bulan + ' (diterapkan ' + data.info.diterapkan + ')');
                }

                $('#rekArea').removeClass('hidden');
            } else {
                $('#rekKosong').removeClass('hidden');
            }
        })
        .catch(() => {
            $('#rekLoadingArea').addClass('hidden');
            $('#rekKosong').removeClass('hidden');
        });
});

window.tutupModalRek = function() {
    $('#modalRekomendasi').addClass('hidden');
    _currentProdukId = null;
}

window.tambahProdukLangsung = function() {
    if (!_currentProdukId) return;
    
    const idToTambah = _currentProdukId; // Simpan ID sebelum modal ditutup
    tutupModalRek();

    fetch('{{ route("kasir.add-cart") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'id=' + idToTambah,
    }).then(() => {
        reloadCart();
        if (typeof Swal !== 'undefined') {
            Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Produk ditambahkan ke keranjang', timer: 1200, showConfirmButton: false });
        }
    });
}

window.tambahRekomendasiDanLanjut = function(namaProduk) {
    if (!_currentProdukId) return;
    
    const idUtama = _currentProdukId; // Simpan ID
    
    // 1. Tambah produk utama dulu
    fetch('{{ route("kasir.add-cart") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'id=' + idUtama,
    }).then(() => {
        // 2. Tambah produk rekomendasi via API nama
        fetch('{{ route("kasir.add-cart-by-name") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'nama=' + encodeURIComponent(namaProduk),
        })
        .then(res => res.json())
        .then(data => {
            reloadCart();
            tutupModalRek();
            if (typeof Swal !== 'undefined') {
                Swal.fire({ 
                    icon: 'success', 
                    title: 'Berhasil!', 
                    text: 'Produk utama & rekomendasi (' + namaProduk + ') ditambahkan ke keranjang.', 
                    timer: 1500, 
                    showConfirmButton: false 
                });
            }
        })
        .catch(() => {
            reloadCart();
            tutupModalRek();
        });
    });
}

$(function () {
    loadData(1);
    reloadCart();

    $('#dibayar').on('input', hitungKembalian);

    // LOGIKA TIPE ORDER & BOOKING
    $('#pesanan').on('change', function() {
        const val = $(this).val();
        const bookingFields = $('#booking-fields');
        
        if (val === 'Booking') {
            bookingFields.removeClass('hidden');
            $('#status').val('Bayar Nanti').trigger('change');
        } else {
            bookingFields.addClass('hidden');
        }
    });

    $('#AddKasir').on('submit', function (e) {
        e.preventDefault();
        
        const tipeOrder = $('#pesanan').val();
        
        if (tipeOrder === 'Booking') {
            const tgl = $('#booking_date').val();
            const jam = $('#booking_time').val();
            const nama = $('#atas_nama').val();
            
            if (!tgl || !jam || !nama) {
                Swal.fire({ icon: 'warning', title: 'Data Belum Lengkap', text: 'Mohon isi Nama, Tanggal, dan Jam Booking.' });
                return;
            }
            
            // Validasi Cart Kosong
            const items = document.querySelectorAll('#cart_keranjang .cart-item');
            if (items.length === 0) {
                Swal.fire({ icon: 'error', title: 'Keranjang Kosong', text: 'Pilih produk terlebih dahulu sebelum booking.' });
                return;
            }
        }

        // Simpan Transaksi (Berlaku untuk Ditempat maupun Booking)
         $.post('{{ route('kasir.store') }}', $(this).serialize(), function (noBon) {
             Swal.fire({ 
                 icon: 'success', 
                 title: 'Transaksi berhasil', 
                 text: 'No. Bon: ' + noBon 
             }).then(() => {
                 window.open('{{ url('kasir/show') }}?id=' + noBon, '_blank');
                 location.reload();
             });
         }).fail(function (xhr) {
             Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseText || 'Transaksi gagal' });
         });
     });
 });
</script>
@endpush
