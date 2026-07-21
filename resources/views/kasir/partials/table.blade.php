<table class="w-full text-sm">
    <thead class="bg-slate-50 text-slate-600">
        <tr>
            <th class="px-2 py-2 text-left">Produk</th>
            <th class="px-2 py-2">Qty</th>
            <th class="px-2 py-2 text-right">Subtotal</th>
            <th class="px-2 py-2"></th>
        </tr>
    </thead>
    <tbody>
    @foreach ($items as $item)
        <tr class="border-t border-slate-100">
            <td class="px-2 py-2">{{ $item->nama }}</td>
            <td class="px-2 py-2 text-center">{{ $item->qty }}</td>
            <td class="px-2 py-2 text-right">Rp {{ number_format($item->harga_jual * $item->qty, 0, ',', '.') }}</td>
            <td class="px-2 py-2">
                <button type="button" class="text-red-500 hover:text-red-700 del-item" data-id="{{ $item->id_menu }}"><i class="fa fa-trash"></i></button>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
