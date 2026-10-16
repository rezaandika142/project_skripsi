@extends('layouts.app')

@section('title', 'Masuk Staf Medis - CDSS RSI Kendal')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center py-6">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-sm border border-slate-200 p-8 space-y-6">
        
        <!-- Header / Logo -->
        <div class="text-center space-y-2">
            <div class="w-14 h-14 bg-slate-900 text-sky-400 rounded-2xl flex items-center justify-center mx-auto text-2xl shadow-sm">
                <i class="fa-solid fa-user-doctor"></i>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Portal Dokter & Staf Medis</h1>
            <p class="text-xs text-slate-500 max-w-xs mx-auto leading-relaxed">
                Akses sistem pemantauan audit trail skrining dan manajemen model klinis RSI Kendal.
            </p>
        </div>

        <!-- Tombol Masuk Cepat Demo Sidang (1-Click Login) -->
        <div class="bg-sky-50 border border-sky-200 rounded-xl p-4 space-y-2">
            <div class="flex items-center space-x-2 text-xs font-bold text-sky-900">
                <i class="fa-solid fa-wand-magic-sparkles text-sky-600"></i>
                <span>Akses Cepat Pengujian Sidang</span>
            </div>
            <p class="text-[11px] text-sky-700 leading-relaxed">
                Gunakan tombol akses instan di bawah ini untuk masuk langsung sebagai Dokter Spesialis tanpa perlu mengetik kredensial.
            </p>
            <a href="{{ route('login.quick') }}"
                class="w-full bg-sky-600 hover:bg-sky-500 text-white font-bold py-2.5 px-3 rounded-lg text-xs shadow-sm transition flex items-center justify-center space-x-1.5">
                <i class="fa-solid fa-right-to-bracket"></i>
                <span>Masuk sebagai dr. Ahmad Fauzi, Sp.JP</span>
            </a>
        </div>

        <div class="relative flex items-center justify-center">
            <div class="border-t border-slate-200 w-full"></div>
            <span class="bg-white px-3 text-[11px] font-semibold text-slate-400 uppercase tracking-wider relative">
                Atau Kredensial Manual
            </span>
        </div>

        <!-- Form Login Manual -->
        <form method="POST" action="{{ route('login.submit') }}" class="space-y-4">
            @csrf
            
            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                    Alamat Email
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                        <i class="fa-regular fa-envelope text-xs"></i>
                    </span>
                    <input type="email" id="email" name="email" value="{{ old('email', 'dokter@rsikendal.co.id') }}" required
                        class="w-full pl-9 pr-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 outline-none transition">
                </div>
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                    Kata Sandi
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                        <i class="fa-solid fa-lock text-xs"></i>
                    </span>
                    <input type="password" id="password" name="password" value="password123" required
                        class="w-full pl-9 pr-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 outline-none transition">
                </div>
            </div>

            <button type="submit"
                class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-2.5 px-4 rounded-lg text-xs shadow-sm transition flex items-center justify-center space-x-1.5">
                <i class="fa-solid fa-arrow-right-to-bracket"></i>
                <span>Masuk Sistem</span>
            </button>
        </form>

        <div class="text-center pt-2 border-t border-slate-100">
            <a href="{{ route('skrining.index') }}" class="text-xs text-sky-600 hover:text-sky-800 font-semibold transition">
                &larr; Kembali ke Halaman Skrining Mandiri
            </a>
        </div>

    </div>
</div>
@endsection
