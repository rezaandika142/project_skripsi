<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'HIPERTENSI-CARE') - CDSS RSI Kendal</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        /*
                         * Palette rationale (R-01, R-31):
                         * stone-* — warmer than slate, matches clinical warmth for general-patient audience
                         * sky-700  — single accent, calm medical blue, CTAs only (one deliberate accent, Part 3)
                         * red-600  — danger signal for high-risk results (functional, not decorative)
                         * emerald-600 — safe signal for low-risk results (functional, not decorative)
                         */
                        brand: {
                            navy: '#0f172a',
                            blue: '#0284c7',
                            sky: '#0369a1',
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
        /*
         * System font stack: no custom font import needed; system-ui is neutral and fast.
         * Background stone-50 is warm (#fafaf9) not cold sterile white — suits warm/accessible character.
         */
        body {
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #fafaf9; /* stone-50 */
            color: #1c1917; /* stone-900 */
        }
        @media print {
            .no-print { display: none !important; }
        }
        /* Remove spinner arrows on number inputs (accessibility + UX) */
        input[type=number]::-webkit-outer-spin-button,
        input[type=number]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type=number] {
            -moz-appearance: textfield;
            appearance: textfield;
        }
        /* Active nav link underline — signals current location without a background pill */
        .nav-active {
            color: #0369a1;
            border-bottom: 2px solid #0369a1;
        }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen flex flex-col bg-stone-50 antialiased text-stone-800">

    <!--
        Navigation: white background, stone-200 border.
        Rationale (R-21): light nav fits warm, approachable, general-patient character.
        Dark nav was forcing a "tech" aesthetic with no brand reason — removed (antislop-ui: Dark Mode Default for No Reason).
    -->
    <header class="bg-white border-b border-stone-200 sticky top-0 z-50 no-print">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                <!-- Brand -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('skrining.index') }}" class="flex items-center gap-2 hover:opacity-80 transition">
                        <i class="fa-solid fa-heart-pulse text-red-500 text-lg"></i>
                        <span class="font-bold text-stone-900 text-base tracking-tight">HIPERTENSI-CARE</span>
                    </a>
                    <span class="hidden lg:inline-block text-stone-400 text-xs pl-3 border-l border-stone-200">
                        CDSS Web · RSI Kendal
                    </span>
                </div>

                <!-- Desktop Nav -->
                <nav class="hidden md:flex items-center gap-1 text-sm font-medium">
                    <a href="{{ route('skrining.index') }}"
                       class="px-3 py-1.5 rounded-md transition {{ request()->routeIs('skrining.*') ? 'text-sky-700 bg-sky-50' : 'text-stone-600 hover:text-stone-900 hover:bg-stone-100' }}">
                        <i class="fa-solid fa-stethoscope mr-1.5 text-xs"></i>Skrining Mandiri
                    </a>

                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="px-3 py-1.5 rounded-md transition {{ request()->routeIs('dashboard') ? 'text-sky-700 bg-sky-50' : 'text-stone-600 hover:text-stone-900 hover:bg-stone-100' }}">
                            <i class="fa-solid fa-chart-line mr-1.5 text-xs"></i>Dasbor
                        </a>
                        <a href="{{ route('riwayat.index') }}"
                           class="px-3 py-1.5 rounded-md transition {{ request()->routeIs('riwayat.*') ? 'text-sky-700 bg-sky-50' : 'text-stone-600 hover:text-stone-900 hover:bg-stone-100' }}">
                            <i class="fa-solid fa-clock-rotate-left mr-1.5 text-xs"></i>Riwayat
                        </a>
                    @endauth

                    <!-- Auth State -->
                    <div class="pl-2 ml-1 border-l border-stone-200 flex items-center gap-2">
                        @auth
                            <span class="text-xs text-stone-500 flex items-center gap-1.5">
                                <i class="fa-solid fa-user-doctor text-sky-600"></i>
                                <span class="max-w-[120px] truncate text-stone-700 font-semibold">{{ Auth::user()->name }}</span>
                            </span>
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" title="Keluar dari sesi"
                                        class="text-xs text-stone-400 hover:text-red-500 px-2 py-1.5 rounded transition">
                                    <i class="fa-solid fa-right-from-bracket"></i>
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}"
                               class="text-xs font-semibold text-sky-700 hover:text-sky-800 border border-sky-200 hover:border-sky-400 bg-sky-50 hover:bg-sky-100 px-3 py-1.5 rounded-md transition flex items-center gap-1.5">
                                <i class="fa-solid fa-lock text-xs"></i>Portal Petugas
                            </a>
                        @endauth
                    </div>
                </nav>

                <!-- Mobile: user chip + hamburger -->
                <div class="flex md:hidden items-center gap-2">
                    @auth
                        <span class="text-[11px] bg-sky-50 text-sky-700 border border-sky-200 px-2 py-1 rounded font-semibold truncate max-w-[90px]">
                            {{ Auth::user()->name }}
                        </span>
                    @endauth
                    <button type="button" id="mobileMenuBtn" onclick="toggleMobileMenu()"
                            class="min-w-[44px] min-h-[44px] flex items-center justify-center rounded-lg text-stone-500 hover:text-stone-900 hover:bg-stone-100 transition"
                            aria-label="Buka menu navigasi">
                        <i class="fa-solid fa-bars text-lg" id="mobileMenuIcon"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Drawer -->
            <div id="mobileMenu" class="hidden md:hidden border-t border-stone-100 py-3 space-y-1 pb-4">
                <a href="{{ route('skrining.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('skrining.*') ? 'text-sky-700 bg-sky-50' : 'text-stone-600 hover:bg-stone-100 hover:text-stone-900' }}">
                    <i class="fa-solid fa-stethoscope w-5 text-center text-sky-600"></i>
                    <span>Skrining Mandiri</span>
                </a>

                @auth
                    <a href="{{ route('dashboard') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('dashboard') ? 'text-sky-700 bg-sky-50' : 'text-stone-600 hover:bg-stone-100 hover:text-stone-900' }}">
                        <i class="fa-solid fa-chart-line w-5 text-center text-sky-600"></i>
                        <span>Dasbor Monitoring</span>
                    </a>
                    <a href="{{ route('riwayat.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('riwayat.*') ? 'text-sky-700 bg-sky-50' : 'text-stone-600 hover:bg-stone-100 hover:text-stone-900' }}">
                        <i class="fa-solid fa-clock-rotate-left w-5 text-center text-sky-600"></i>
                        <span>Riwayat &amp; Audit</span>
                    </a>
                    <div class="pt-2 mt-1 border-t border-stone-100 flex items-center justify-between px-3">
                        <div class="text-xs text-stone-500">
                            Masuk sebagai: <strong class="text-stone-800 block">{{ Auth::user()->name }}</strong>
                        </div>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit"
                                    class="text-xs bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 px-3 py-1.5 rounded-md font-semibold transition flex items-center gap-1">
                                <i class="fa-solid fa-right-from-bracket"></i>Keluar
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-stone-600 hover:bg-stone-100 hover:text-stone-900 transition">
                        <i class="fa-solid fa-lock w-5 text-center text-sky-600"></i>
                        <span>Portal Petugas</span>
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Flash Notifications -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full no-print">
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg flex items-center justify-between text-sm mb-4">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-emerald-600"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 font-bold">&times;</button>
            </div>
        @endif
        @if(session('info'))
            <div class="bg-sky-50 border border-sky-200 text-sky-800 px-4 py-3 rounded-lg flex items-center justify-between text-sm mb-4">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-info text-sky-600"></i>
                    <span>{{ session('info') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-sky-600 hover:text-sky-800 font-bold">&times;</button>
            </div>
        @endif
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm mb-4">
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

    <!-- Page Content -->
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-5 sm:py-7">
        @yield('content')
    </main>

    <!-- Footer: simple, two-column. Content matches what actually exists. (R-05: no 4-column template footer) -->
    <footer class="bg-white border-t border-stone-200 py-4 text-xs text-stone-500 no-print mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-2 text-center sm:text-left">
            <div class="flex flex-col sm:flex-row items-center sm:items-baseline gap-1 sm:gap-2">
                <span class="font-semibold text-stone-700">RSI Kendal</span>
                <span class="hidden sm:inline text-stone-300">&bull;</span>
                <span>Sistem Skrining Mandiri Hipertensi (CDSS Web)</span>
            </div>
            <div class="flex items-center gap-3 text-stone-400 text-[11px]">
                <span>Engine: <strong class="text-stone-600">ONNX Runtime Web</strong></span>
                <span class="hidden sm:inline">&bull;</span>
                <span>Model: <strong class="text-stone-600">ExtraTrees v1.2</strong></span>
            </div>
        </div>
    </footer>

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            const icon = document.getElementById('mobileMenuIcon');
            if (menu.classList.contains('hidden')) {
                menu.classList.remove('hidden');
                icon.className = 'fa-solid fa-xmark text-lg';
            } else {
                menu.classList.add('hidden');
                icon.className = 'fa-solid fa-bars text-lg';
            }
        }

        // Prevent scroll from changing number input values
        document.addEventListener('wheel', function(e) {
            if (document.activeElement && document.activeElement.type === 'number') {
                document.activeElement.blur();
            }
        }, { passive: true });
    </script>
    @stack('scripts')
</body>
</html>
