@extends('layouts.app')

@section('title', 'Riwayat Skrining & Audit Trail')

@section('content')
<div class="space-y-6">

    <!-- Top Header Banner -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-sky-600 font-semibold text-xs tracking-wider uppercase mb-1">
                <i class="fa-solid fa-clock-rotate-left"></i>
                <span>Log Transaksi & Evaluasi Klinis</span>
            </div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Riwayat Skrining Mandiri</h1>
            <p class="text-xs text-slate-600 mt-1 max-w-2xl leading-relaxed">
                Catatan terperinci riwayat evaluasi risiko hipertensi pasien RSI Kendal secara transparan untuk keperluan audit medis dan tindak lanjut poliklinik.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('riwayat.export') }}" class="bg-emerald-600 hover:bg-emerald-500 text-white font-bold px-3.5 py-2 rounded-lg text-xs shadow-sm transition flex items-center space-x-1.5">
                <i class="fa-solid fa-file-csv"></i>
                <span>Ekspor CSV</span>
            </a>
            <a href="{{ route('skrining.index') }}" class="bg-sky-600 hover:bg-sky-500 text-white font-bold px-3.5 py-2 rounded-lg text-xs shadow-sm transition flex items-center space-x-1.5">
                <i class="fa-solid fa-plus"></i>
                <span>Skrining Baru</span>
            </a>
        </div>
    </div>

    <!-- Filter & Pencarian Bar -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
        <form method="GET" action="{{ route('riwayat.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
            
            <!-- Input Cari Nama -->
            <div class="sm:col-span-6 relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </span>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari berdasarkan nama pasien..."
                    class="w-full pl-9 pr-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 outline-none transition">
            </div>

            <!-- Dropdown Status -->
            <div class="sm:col-span-3">
                <select name="status" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 outline-none transition">
                    <option value="">Semua Status Risiko</option>
                    <option value="RISIKO TINGGI" {{ request('status') == 'RISIKO TINGGI' ? 'selected' : '' }}>Risiko Tinggi</option>
                    <option value="NORMOTENSI" {{ request('status') == 'NORMOTENSI' ? 'selected' : '' }}>Normotensi</option>
                </select>
            </div>

            <!-- Tombol Filter & Reset -->
            <div class="sm:col-span-3 flex items-center space-x-2">
                <button type="submit" class="flex-1 bg-slate-800 hover:bg-slate-900 text-white font-bold py-2 px-3 rounded-lg text-xs transition flex items-center justify-center space-x-1">
                    <i class="fa-solid fa-filter"></i>
                    <span>Terapkan</span>
                </button>
                @if(request('q') || request('status'))
                    <a href="{{ route('riwayat.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold py-2 px-3 rounded-lg text-xs transition" title="Reset filter">
                        Reset
                    </a>
                @endif
            </div>

        </form>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        
        <div class="p-4 border-b border-slate-100 flex items-center justify-between text-xs text-slate-500">
            <div>
                Menampilkan <span class="font-bold text-slate-800">{{ $predictions->firstItem() ?? 0 }}</span> sampai <span class="font-bold text-slate-800">{{ $predictions->lastItem() ?? 0 }}</span> dari total <span class="font-bold text-slate-800">{{ $predictions->total() }}</span> catatan.
            </div>
            <div class="text-[11px] text-slate-400">
                Diurutkan berdasarkan waktu pemeriksaan terbaru
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 uppercase tracking-wider font-bold border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-3 text-center">ID</th>
                        <th class="py-3 px-3">Waktu Pemeriksaan</th>
                        <th class="py-3 px-3">Nama Pasien</th>
                        <th class="py-3 px-3">Gender / Usia</th>
                        <th class="py-3 px-3">BB / TB</th>
                        <th class="py-3 px-3">IMT</th>
                        <th class="py-3 px-3">Probabilitas</th>
                        <th class="py-3 px-3">Klasifikasi Risiko</th>
                        <th class="py-3 px-3 text-center">Aksi Medis</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($predictions as $item)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-3 text-center font-mono text-[11px] text-slate-400">
                                #{{ $item->id_prediction }}
                            </td>
                            <td class="py-3.5 px-3 font-mono text-[11px] text-slate-600 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($item->tanggal_prediksi)->format('d/m/Y H:i') }}
                            </td>
                            <td class="py-3.5 px-3 font-bold text-slate-900">
                                {{ $item->nama_pengguna }}
                            </td>
                            <td class="py-3.5 px-3">
                                {{ $item->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}, {{ $item->usia }} thn
                            </td>
                            <td class="py-3.5 px-3 font-medium">
                                {{ $item->berat_badan }} kg / {{ $item->tinggi_badan }} cm
                            </td>
                            <td class="py-3.5 px-3 font-semibold text-slate-800">
                                {{ $item->bmi }}
                            </td>
                            <td class="py-3.5 px-3 font-black text-slate-900">
                                {{ $item->probabilitas }}%
                            </td>
                            <td class="py-3.5 px-3 whitespace-nowrap">
                                @if($item->status_hipertensi == 'RISIKO TINGGI')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold bg-red-100 text-red-800 border border-red-200">
                                        <i class="fa-solid fa-triangle-exclamation mr-1.5 text-red-600"></i> Risiko Tinggi
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        <i class="fa-solid fa-circle-check mr-1.5 text-emerald-600"></i> Normotensi
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-3 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('skrining.rujukan', $item->id_prediction) }}" target="_blank"
                                        class="text-xs bg-slate-100 hover:bg-sky-50 text-sky-700 hover:text-sky-800 font-bold px-2.5 py-1.5 rounded border border-slate-200 hover:border-sky-200 transition"
                                        title="Cetak Surat Rujukan Medis">
                                        <i class="fa-solid fa-print mr-1"></i> Cetak
                                    </a>
                                    <form action="{{ route('riwayat.destroy', $item->id_prediction) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus data audit skrining pasien ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs bg-slate-100 hover:bg-red-50 text-slate-400 hover:text-red-600 p-1.5 rounded border border-slate-200 hover:border-red-200 transition" title="Hapus catatan">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-10 text-slate-400">
                                <i class="fa-solid fa-inbox text-3xl mb-2 block"></i>
                                <span class="text-xs">Tidak ditemukan riwayat skrining yang sesuai kriteria pencarian.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($predictions->hasPages())
            <div class="p-4 border-t border-slate-200 bg-slate-50 flex items-center justify-center">
                {{ $predictions->links() }}
            </div>
        @endif

    </div>

</div>
@endsection
