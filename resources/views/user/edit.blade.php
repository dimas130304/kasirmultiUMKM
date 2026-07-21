@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto">
    <x-page-card title="Pengaturan Profil" icon="fa-user-circle">
        <form method="POST" action="{{ route('user.upd') }}" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @csrf
            {{-- Foto Profil --}}
            <div class="md:col-span-1 flex flex-col items-center space-y-4">
                <div class="relative group">
                    <div class="w-40 h-40 rounded-2xl overflow-hidden border-4 border-slate-100 shadow-sm bg-slate-50 flex items-center justify-center">
                        @if($user->foto && $user->foto !== '-' && file_exists(public_path('assets/image/user/'.$user->foto)))
                            <img id="previewFoto" src="{{ asset('assets/image/user/'.$user->foto) }}" class="w-full h-full object-cover">
                        @else
                            <i id="placeholderIcon" class="fa fa-user text-6xl text-slate-300"></i>
                            <img id="previewFoto" class="w-full h-full object-cover hidden">
                        @endif
                    </div>
                    <label for="inputFoto" class="absolute inset-0 flex items-center justify-center bg-black/40 text-white opacity-0 group-hover:opacity-100 transition cursor-pointer rounded-2xl">
                        <div class="text-center">
                            <i class="fa fa-camera text-2xl mb-1"></i>
                            <p class="text-[10px] font-bold uppercase tracking-wider">Ubah Foto</p>
                        </div>
                    </label>
                    <input type="file" name="foto" id="inputFoto" class="hidden" accept="image/*" onchange="previewImage(this)">
                </div>
                <div class="text-center">
                    <h4 class="font-bold text-slate-800">{{ $user->nama_user }}</h4>
                    <p class="text-xs text-slate-500 uppercase tracking-widest font-medium">{{ $user->level }}</p>
                </div>
                <p class="text-[10px] text-slate-400 text-center leading-relaxed">
                    * Gunakan format JPG, PNG, atau JPEG.<br>Maksimal ukuran file 2MB.
                </p>
            </div>

            {{-- Form Data --}}
            <div class="md:col-span-2 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Lengkap</label>
                        <input name="nama" value="{{ $user->nama_user }}" required class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Username</label>
                        <input name="user" value="{{ $user->user }}" required class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Email</label>
                    <input name="email" type="email" value="{{ $user->email }}" required class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition">
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Nomor Telepon</label>
                    <input name="telepon" value="{{ $user->telepon }}" required class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition">
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Alamat</label>
                    <textarea name="alamat" rows="2" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition">{{ $user->alamat }}</textarea>
                </div>

                <div class="pt-4 border-t border-slate-100">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Ganti Password</label>
                        <input type="password" name="pass" placeholder="Isi hanya jika ingin mengganti password" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition">
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full md:w-auto rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-bold px-8 py-3 shadow-lg shadow-teal-600/20 transition transform active:scale-95">
                        <i class="fa fa-save mr-2"></i> Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </x-page-card>
</div>

<script>
function previewImage(input) {
    const preview = document.getElementById('previewFoto');
    const placeholder = document.getElementById('placeholderIcon');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            if (placeholder) placeholder.classList.add('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
