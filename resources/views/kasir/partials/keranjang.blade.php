@if(count($items) > 0)
    <div class="divide-y divide-slate-100 max-h-72 overflow-y-auto custom-scrollbar" id="cart-list">
    @php $total = 0; @endphp
    @foreach ($items as $item)
        @php $sub = $item->harga_jual * $item->qty; $total += $sub; @endphp
        <div class="cart-item px-3 py-2.5 flex gap-3 text-sm group hover:bg-slate-50 transition-colors" data-id="{{ $item->id_menu }}" data-nama="{{ $item->nama }}" data-qty="{{ $item->qty }}">
            <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-1">
                    <p class="font-bold text-slate-800 truncate leading-tight">{{ $item->nama }}</p>
                    {{-- Tombol Hapus --}}
                    <button type="button"
                        title="Hapus item"
                        onclick="hapusCartItem({{ $item->id_menu }})"
                        class="flex-shrink-0 h-6 w-6 rounded-full bg-red-50 hover:bg-red-100 text-red-400 hover:text-red-600 flex items-center justify-center transition-colors ml-1">
                        <i class="fa fa-times text-xs"></i>
                    </button>
                </div>
                <div class="flex items-center justify-between mt-1.5">
                    <div class="flex items-center gap-1.5">
                        {{-- Tombol Kurang --}}
                        <button type="button"
                            onclick="updateQty({{ $item->id_menu }}, 'minus')"
                            class="h-6 w-6 rounded-full bg-slate-200 hover:bg-slate-300 text-slate-600 flex items-center justify-center text-xs font-bold transition-colors">
                            <i class="fa fa-minus"></i>
                        </button>
                        <span class="text-xs font-bold text-slate-700 min-w-[20px] text-center">{{ $item->qty }}</span>
                        {{-- Tombol Tambah --}}
                        <button type="button"
                            onclick="updateQty({{ $item->id_menu }}, 'plus')"
                            class="h-6 w-6 rounded-full bg-teal-100 hover:bg-teal-200 text-teal-700 flex items-center justify-center text-xs font-bold transition-colors">
                            <i class="fa fa-plus"></i>
                        </button>
                        <span class="text-[11px] text-slate-400 ml-1">× Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</span>
                    </div>
                    <div class="font-black text-teal-600 text-sm">Rp {{ number_format($sub, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    @endforeach
    </div>
@else
    <div class="flex flex-col items-center justify-center py-10 px-4 text-center">
        <div class="h-14 w-14 rounded-full bg-slate-50 flex items-center justify-center mb-3">
            <i class="fa fa-shopping-basket text-3xl text-slate-200"></i>
        </div>
        <p class="text-sm font-bold text-slate-400">Keranjang masih kosong</p>
        <p class="text-[10px] text-slate-300 mt-1 uppercase tracking-widest font-bold">Pilih produk di sebelah kiri</p>
    </div>
    @php $total = 0; @endphp
@endif

<input type="hidden" id="raw_total" value="{{ $total }}">
