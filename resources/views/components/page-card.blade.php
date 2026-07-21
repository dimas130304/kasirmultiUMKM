@props(['title', 'icon' => 'fa-list'])
<div class="bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.05)] border border-slate-100 overflow-hidden">
    <div class="bg-gradient-to-r from-teal-600 via-teal-500 to-emerald-500 px-7 py-5 text-white">
        <div class="flex items-center gap-3">
            <div class="h-11 w-11 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center text-xl">
                <i class="fa {{ $icon }}"></i>
            </div>
            <h2 class="text-xl font-bold tracking-tight">{{ $title }}</h2>
        </div>
    </div>
    <div class="p-7">
        {{ $slot }}
    </div>
</div>
