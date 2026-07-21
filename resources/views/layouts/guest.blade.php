<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Autentikasi') &mdash; KASIR MULTI-UMKM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            teal: '#0d9488',
                            'teal-light': '#14b8a6',
                            navy: '#0f172a',
                            'navy-mid': '#1e3a5f',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .auth-gradient {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 45%, #0d9488 100%);
        }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen auth-gradient text-slate-100 antialiased">
    <div class="min-h-screen flex flex-col">
        <header class="border-b border-white/10 bg-slate-900/40 backdrop-blur-sm">
            <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
                <a href="{{ route('landing') }}" class="flex items-center gap-3 group">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-teal-500/20 text-teal-300 ring-1 ring-teal-400/30">
                        <i class="fa fa-shopping-bag text-lg"></i>
                    </span>
                    <span>
                        <span class="block font-bold text-white tracking-wide">KASIR MULTI UMKM</span>
                        <span class="block text-xs text-teal-200/80">Sistem POS &amp; Multi-UMKM</span>
                    </span>
                </a>
                <a href="{{ route('landing') }}" class="text-sm text-slate-300 hover:text-white transition">
                    <i class="fa fa-home mr-1"></i> Beranda
                </a>
            </div>
        </header>

        <main class="flex-1 flex items-center justify-center px-4 py-10">
            <div class="w-full max-w-lg">
                @if (session('success'))
                    <div class="mb-4 rounded-xl border border-emerald-400/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-100">
                        {!! session('success') !!}
                    </div>
                @endif
                @if (session('failed') || session('error'))
                    <div class="mb-4 rounded-xl border border-red-400/30 bg-red-500/10 px-4 py-3 text-sm text-red-100">
                        {!! session('failed') ?? session('error') !!}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="mb-4 rounded-xl border border-red-400/30 bg-red-500/10 px-4 py-3 text-sm text-red-100">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="rounded-2xl bg-white shadow-2xl shadow-slate-900/40 text-slate-800 overflow-hidden">
                    @yield('content')
                </div>

                <p class="mt-6 text-center text-xs text-slate-300/80">
                    &copy; {{ date('Y') }} KASIR MULTI UMKM &mdash; Sistem POS untuk UMKM
                </p>
            </div>
        </main>
    </div>
    @stack('scripts')
</body>
</html>
