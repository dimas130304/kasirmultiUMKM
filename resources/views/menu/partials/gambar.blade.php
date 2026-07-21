@php
    $img = $gambar ?? '-';
    $size = $size ?? 'md';
    $cls = $size === 'sm' ? 'h-10 w-10' : 'h-14 w-14';
@endphp
@if ($img === '-' || empty($img))
    <div class="{{ $cls }} rounded-lg bg-slate-100 flex items-center justify-center text-slate-400">
        <i class="fa fa-image"></i>
    </div>
@else
    <img src="{{ asset('assets/image/produk/'.$img) }}" alt="" class="{{ $cls }} rounded-lg object-cover border border-slate-200">
@endif
