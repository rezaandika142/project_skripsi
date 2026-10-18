@extends('layouts.app')

@section('title', 'Masuk Staf Medis - CDSS RSI Kendal')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center py-6 sm:py-10 px-2">
    <div class="max-w-md w-full bg-white rounded-2xl border border-stone-200 p-6 sm:p-8 space-y-6">

        {{--
            Header: Warm clinical icon, direct human headline.
            Removed uppercase tracking slop and generic dark container.
        --}}
        <div class="text-center space-y-2">
            <div class="w-12 h-12 sm:w-14 sm:h-14 bg-sky-50 text-sky-700 border border-sky-200 rounded-2xl flex items-center justify-center mx-auto text-xl sm:text-2xl">
                <i class="fa-solid fa-user-doctor"></i>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-stone-900 tracking-tight">Portal Staf Medis</h1>
            <p class="text-xs sm:text-sm text-stone-500 max-w-xs mx-auto leading-relaxed">
                Akses sistem pemantauan audit trail skrining dan manajemen model klinis RSI Kendal.
            </p>
        </div>

        {{--
            1-Click Login for trial / demo.
            Replaced generic AI icon (fa-wand-magic-sparkles) with direct access indicator (fa-bolt).
        --}}
        <div class="bg-stone-50 border border-stone-200 rounded-xl p-4 space-y-2.5">
            <div class="flex items-center gap-2 text-xs font-semibold text-stone-800">
                <i class="fa-solid fa-bolt text-sky-600"></i>
                <span>Akses Cepat Pengujian Sidang</span>
            </div>
            <p class="text-xs text-stone-500 leading-relaxed">
                Gunakan tombol akses instan berikut untuk masuk langsung sebagai dokter spesialis penanggung jawab tanpa mengetik manual.
            </p>
            <a href="{{ route('login.quick') }}"
               class="w-full min-h-[44px] bg-sky-700 hover:bg-sky-600 active:scale-[0.99] text-white font-semibold py-2.5 px-3 rounded-lg text-xs shadow-sm transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-right-to-bracket"></i>
                <span>Masuk sebagai dr. Ahmad Fauzi, Sp.JP</span>
            </a>
        </div>

        <div class="relative flex items-center justify-center">
            <div class="border-t border-stone-200 w-full"></div>
            <span class="bg-white px-3 text-xs font-medium text-stone-400 relative">
                atau kredensial manual
            </span>
        </div>

        <!-- Form Login Manual -->
        <form method="POST" action="{{ route('login.submit') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-stone-700 mb-1">
                    Alamat Email
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-stone-400 pointer-events-none">
                        <i class="fa-regular fa-envelope text-sm"></i>
                    </span>
                    <input type="email" id="email" name="email" value="{{ old('email', 'dokter@rsikendal.co.id') }}" required inputmode="email"
                           class="w-full pl-9 pr-3 py-2.5 text-sm min-h-[44px] border border-stone-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 outline-none transition bg-white text-stone-900 placeholder:text-stone-400">
                </div>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-stone-700 mb-1">
                    Kata Sandi
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-stone-400 pointer-events-none">
                        <i class="fa-solid fa-lock text-sm"></i>
                    </span>
                    <input type="password" id="password" name="password" value="password123" required
                           class="w-full pl-9 pr-3 py-2.5 text-sm min-h-[44px] border border-stone-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 outline-none transition bg-white text-stone-900">
                </div>
            </div>

            <button type="submit"
                    class="w-full min-h-[44px] bg-stone-900 hover:bg-stone-800 active:scale-[0.99] text-white font-semibold py-2.5 px-4 rounded-lg text-sm shadow-sm transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-arrow-right-to-bracket text-xs"></i>
                <span>Masuk Sistem</span>
            </button>
        </form>

        <div class="text-center pt-2 border-t border-stone-100">
            <a href="{{ route('skrining.index') }}" class="text-xs text-sky-700 hover:text-sky-800 font-medium transition inline-flex items-center gap-1">
                <i class="fa-solid fa-arrow-left text-[10px]"></i>
                <span>Kembali ke Halaman Skrining Mandiri</span>
            </a>
        </div>

    </div>
</div>
@endsection
