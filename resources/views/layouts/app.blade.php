<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'HIPERTENSI-CARE') - Clinical Decision Support System RSI Kendal</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            navy: '#0f172a',
                            dark: '#1e293b',
                            blue: '#0284c7',
                            sky: '#38bdf8',
                            emerald: '#16a34a',
                            crimson: '#dc2626'
                        }
                    }
                }
            }
        }
    </script>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Chart.js for Dashboard Visualizations -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- ONNX Runtime Web Library (WASM / WebGL Execution) -->
    <script src="https://cdn.jsdelivr.net/npm/onnxruntime-web/dist/ort.min.js"></script>

    <style>
        body {
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f8fafc;
            color: #0f172a;
        }
        @media print {
            .no-print { display: none !important; }
        }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen flex flex-col bg-slate-50 antialiased text-slate-800">

    <!-- Top Navigation Bar (Sesuai Desain Bab III & Bab IV) -->
    <header class="bg-[#0f172a] text-white border-b border-slate-800 sticky top-0 z-50 shadow-md no-print">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand Title -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('skrining.index') }}" class="flex items-center space-x-2 text-white font-extrabold text-lg tracking-wider hover:text-sky-400 transition">
                        <i class="fa-solid fa-heart-pulse text-red-500 text-xl"></i>
                        <span>HIPERTENSI-CARE</span>
                    </a>
                    <span class="hidden md:inline-block text-slate-400 text-xs font-medium pl-3 border-l border-slate-700">
                        Clinical Decision Support System (CDSS Web - RSI Kendal)
                    </span>
                </div>

                <!-- Main Menu Links -->
                <nav class="flex items-center space-x-1 sm:space-x-3 text-sm font-semibold">
                    <!-- Menu Publik: Skrining Mandiri (Selalu Tampil) -->
                    <a href="{{ route('skrining.index') }}" class="px-3 py-2 rounded-md transition {{ request()->routeIs('skrining.*') ? 'bg-sky-500/20 text-sky-400 border border-sky-500/40' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                        <i class="fa-solid fa-stethoscope mr-1.5"></i>Skrining Mandiri
                    </a>

                    <!-- Menu Khusus Internal Tenaga Medis (Hanya Tampil Jika Sudah Login) -->
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-md transition {{ request()->routeIs('dashboard') ? 'bg-sky-500/20 text-sky-400 border border-sky-500/40' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            <i class="fa-solid fa-chart-line mr-1.5"></i>Dasbor
                        </a>
                        <a href="{{ route('riwayat.index') }}" class="px-3 py-2 rounded-md transition {{ request()->routeIs('riwayat.*') ? 'bg-sky-500/20 text-sky-400 border border-sky-500/40' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            <i class="fa-solid fa-clock-rotate-left mr-1.5"></i>Riwayat & Audit
                        </a>
                    @endauth

                    <!-- Auth State -->
                    <div class="pl-2 border-l border-slate-700 flex items-center">
                        @auth
                            <div class="flex items-center space-x-2">
                                <span class="text-xs bg-slate-800 text-slate-300 px-2.5 py-1.5 rounded-md border border-slate-700 flex items-center" title="Sesi aktif staf medis">
                                    <i class="fa-solid fa-user-doctor text-sky-400 mr-1.5"></i>
                                    <span class="max-w-[130px] truncate">{{ Auth::user()->name }}</span>
                                </span>
                                <form action="{{ route('logout') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" title="Keluar dari sesi dokter" class="text-xs text-slate-400 hover:text-red-400 px-2 py-1.5 transition">
                                        <i class="fa-solid fa-right-from-bracket"></i>
                                    </button>
                                </form>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="text-xs bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white font-medium px-3 py-1.5 rounded-md border border-slate-700 shadow-sm transition flex items-center">
                                <i class="fa-solid fa-lock mr-1.5 text-sky-400"></i>Portal Petugas
                            </a>
                        @endauth
                    </div>
                </nav>
            </div>
        </div>
    </header>

    <!-- Notification Alerts -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full no-print">
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-300 text-emerald-800 px-4 py-3 rounded-lg flex items-center justify-between text-sm shadow-sm mb-4">
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-lg"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 text-sm font-bold">&times;</button>
            </div>
        @endif
        @if(session('info'))
            <div class="bg-sky-50 border border-sky-300 text-sky-800 px-4 py-3 rounded-lg flex items-center justify-between text-sm shadow-sm mb-4">
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-circle-info text-sky-600 text-lg"></i>
                    <span>{{ session('info') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-sky-600 hover:text-sky-800 text-sm font-bold">&times;</button>
            </div>
        @endif
        @if($errors->any())
            <div class="bg-red-50 border border-red-300 text-red-800 px-4 py-3 rounded-lg text-sm shadow-sm mb-4">
                <div class="font-bold flex items-center mb-1">
                    <i class="fa-solid fa-triangle-exclamation mr-1.5 text-red-600"></i>Perhatian:
                </div>
                <ul class="list-disc list-inside text-xs space-y-0.5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Main Dynamic Content -->
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
        @yield('content')
    </main>

    <!-- Academic & Clinical Footer -->
    <footer class="bg-white border-t border-slate-200 py-4 text-xs text-slate-500 no-print mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-2">
            <div class="flex items-center space-x-2">
                <span class="font-bold text-slate-700">RSI Kendal &bull; Tugas Akhir</span>
                <span>&ndash; Sistem Skrining Mandiri Hipertensi (CDSS Web)</span>
            </div>
            <div class="text-slate-400">
                Engine: <span class="font-semibold text-slate-600">ONNX Runtime Web (WASM)</span> &bull; 
                Model: <span class="font-semibold text-slate-600">ExtraTrees Classifier v1.2</span>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
