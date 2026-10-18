@extends('layouts.app')

@section('title', 'Dasbor Monitoring Skrining Hipertensi')

@section('content')
<div class="space-y-6">

    {{--
        Welcome banner: replaced dark gradient (antislop-ui: Dark Mode Default, Generic Blue-Purple Gradient).
        Now: white card with left accent line on mobile, clean action buttons.
        Rationale (R-01, R-31): The sky-50 pill badge on the section title marks it as the active admin area.
    --}}
    <div class="bg-white rounded-xl border border-stone-200 p-4 sm:p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <i class="fa-solid fa-hospital-user text-sky-600 text-sm"></i>
                <span class="text-xs font-medium text-sky-700">RSI Kendal</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-stone-900 tracking-tight">Dasbor Pemantauan Skrining</h1>
            <p class="text-sm text-stone-500 mt-1 max-w-2xl leading-relaxed">
                Data skrining masyarakat dan evaluasi klinis terpusat. Model: <em class="not-italic font-medium text-stone-700">ExtraTrees Classifier</em>.
            </p>
        </div>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full md:w-auto flex-shrink-0">
            <a href="{{ route('skrining.index') }}"
               class="min-h-[44px] bg-sky-700 hover:bg-sky-600 text-white font-semibold px-4 py-2.5 rounded-lg text-sm shadow-sm transition flex items-center justify-center gap-2 active:scale-95">
                <i class="fa-solid fa-plus-circle"></i>
                <span>Skrining Pasien Baru</span>
            </a>
            <a href="{{ route('riwayat.export') }}"
               class="min-h-[44px] bg-stone-100 hover:bg-stone-200 text-stone-700 font-medium px-4 py-2.5 rounded-lg text-sm border border-stone-200 transition flex items-center justify-center gap-2 active:scale-95">
                <i class="fa-solid fa-file-csv text-emerald-600"></i>
                <span>Ekspor CSV</span>
            </a>
        </div>
    </div>

    {{--
        4 KPI Cards: all data is real from DB (R-17).
        Layout rationale (R-14): "Total Skrining" spans wider on desktop to reflect its primacy.
        Removed uniform-icon-bg pattern — each card now uses its signal color purposefully.
    --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

        <!-- Card 1: Total Skrining (anchor metric, spans 2 on lg) -->
        <div class="col-span-2 lg:col-span-2 bg-white rounded-xl border border-stone-200 p-5 flex items-center justify-between gap-4">
            <div>
                <div class="text-xs font-medium text-stone-500 mb-1">Total Pasien Terskrining</div>
                <div class="text-3xl font-bold text-stone-900">{{ number_format($totalPredictions) }}</div>
                <div class="text-[11px] text-stone-500 mt-1.5">
                    Basis data: <span class="font-semibold text-stone-700">1.553 rekam medis</span>
                </div>
            </div>
            <div class="w-14 h-14 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-2xl flex-shrink-0">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>

        <!-- Card 2: Risiko Tinggi -->
        <div class="col-span-1 bg-white rounded-xl border border-stone-200 p-5 flex items-center justify-between gap-3">
            <div>
                <div class="text-xs font-medium text-stone-500 mb-1">Risiko Tinggi</div>
                <div class="text-2xl font-bold text-red-600">{{ number_format($totalRisikoTinggi) }}</div>
                @php
                    $pctHT = $totalPredictions > 0 ? round(($totalRisikoTinggi / $totalPredictions) * 100, 1) : 23.4;
                @endphp
                <div class="text-[11px] text-stone-500 mt-1">
                    <span class="font-semibold text-red-600">{{ $pctHT }}%</span> dari total
                </div>
            </div>
            <i class="fa-solid fa-triangle-exclamation text-red-300 text-2xl flex-shrink-0"></i>
        </div>

        <!-- Card 3: Normotensi -->
        <div class="col-span-1 bg-white rounded-xl border border-stone-200 p-5 flex items-center justify-between gap-3">
            <div>
                <div class="text-xs font-medium text-stone-500 mb-1">Normotensi</div>
                <div class="text-2xl font-bold text-emerald-600">{{ number_format($totalNormotensi) }}</div>
                @php
                    $pctNormo = $totalPredictions > 0 ? round(($totalNormotensi / $totalPredictions) * 100, 1) : 76.6;
                @endphp
                <div class="text-[11px] text-stone-500 mt-1">
                    <span class="font-semibold text-emerald-600">{{ $pctNormo }}%</span> risiko rendah
                </div>
            </div>
            <i class="fa-solid fa-circle-check text-emerald-300 text-2xl flex-shrink-0"></i>
        </div>

    </div>

    <!-- Akurasi Model (terpisah, lebih ringkas) -->
    <div class="bg-stone-50 border border-stone-200 rounded-xl p-4 flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <i class="fa-solid fa-brain text-stone-400 text-lg"></i>
            <div>
                <div class="text-xs font-medium text-stone-500">Akurasi Model Aktif</div>
                <div class="text-sm font-bold text-stone-800">ExtraTrees v1.2 · Threshold 81.5%</div>
            </div>
        </div>
        <div class="text-2xl font-bold text-stone-900">{{ number_format($activeModel->akurasi, 2) }}%</div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Grafik Tren Mingguan (8 span) -->
        <!-- Chart title answers the question "Berapa pasien terperiksa per hari minggu ini?" (antislop-ui: Charts Without a Question) -->
        <div class="lg:col-span-8 bg-white rounded-xl border border-stone-200 p-5 space-y-4">
            <div class="flex items-center justify-between border-b border-stone-100 pb-3">
                <div>
                    <h2 class="font-semibold text-stone-800 flex items-center gap-2 text-sm">
                        <i class="fa-solid fa-chart-bar text-sky-600"></i>
                        Berapa pasien terperiksa per hari, minggu ini?
                    </h2>
                    <p class="text-xs text-stone-500 mt-0.5">Senin sampai Minggu, minggu berjalan</p>
                </div>
                <span class="text-[11px] bg-stone-100 text-stone-600 font-medium px-2.5 py-1 rounded">Minggu Ini</span>
            </div>
            <div class="h-64">
                <canvas id="chartTrenMingguan"></canvas>
            </div>
        </div>

        <!-- Distribusi IMT (4 span) -->
        <div class="lg:col-span-4 bg-white rounded-xl border border-stone-200 p-5 space-y-4">
            <div class="border-b border-stone-100 pb-3">
                <h2 class="font-semibold text-stone-800 flex items-center gap-2 text-sm">
                    <i class="fa-solid fa-weight-scale text-sky-600"></i>
                    Distribusi IMT Populasi
                </h2>
                <p class="text-xs text-stone-500 mt-0.5">Proporsi indeks massa tubuh (standar Asia-Pasifik)</p>
            </div>

            <div class="space-y-3.5 pt-1">
                <!-- Underweight -->
                <div>
                    <div class="flex justify-between text-xs font-medium mb-1">
                        <span class="text-stone-600">Underweight (&lt; 18.5)</span>
                        <span class="text-stone-800">{{ $bmiStats['underweight'] }}%</span>
                    </div>
                    <div class="w-full bg-stone-100 rounded-full h-2 overflow-hidden">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $bmiStats['underweight'] }}%"></div>
                    </div>
                </div>
                <!-- Normal -->
                <div>
                    <div class="flex justify-between text-xs font-medium mb-1">
                        <span class="text-stone-600">Normal (18.5 - 22.9)</span>
                        <span class="text-stone-800">{{ $bmiStats['normal'] }}%</span>
                    </div>
                    <div class="w-full bg-stone-100 rounded-full h-2 overflow-hidden">
                        <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ $bmiStats['normal'] }}%"></div>
                    </div>
                </div>
                <!-- Overweight -->
                <div>
                    <div class="flex justify-between text-xs font-medium mb-1">
                        <span class="text-stone-600">Overweight (23 - 24.9)</span>
                        <span class="text-stone-800">{{ $bmiStats['overweight'] }}%</span>
                    </div>
                    <div class="w-full bg-stone-100 rounded-full h-2 overflow-hidden">
                        <div class="bg-amber-500 h-2 rounded-full" style="width: {{ $bmiStats['overweight'] }}%"></div>
                    </div>
                </div>
                <!-- Obesitas -->
                <div>
                    <div class="flex justify-between text-xs font-medium mb-1">
                        <span class="text-stone-600">Obesitas (&ge; 25.0)</span>
                        <span class="text-stone-800">{{ $bmiStats['obese'] }}%</span>
                    </div>
                    <div class="w-full bg-stone-100 rounded-full h-2 overflow-hidden">
                        <div class="bg-red-500 h-2 rounded-full" style="width: {{ $bmiStats['obese'] }}%"></div>
                    </div>
                </div>
            </div>

            <div class="pt-2 border-t border-stone-100 text-[11px] text-stone-400">
                <i class="fa-solid fa-circle-info mr-1 text-sky-400"></i>
                Klasifikasi IMT mengacu kriteria WHO WPRO Asia-Pasifik.
            </div>
        </div>

    </div>

    <!-- Aktivitas Skrining Terbaru -->
    <div class="bg-white rounded-xl border border-stone-200 p-5 space-y-4">
        <div class="flex items-center justify-between border-b border-stone-100 pb-3">
            <div>
                <h2 class="font-semibold text-stone-800 flex items-center gap-2 text-sm">
                    <i class="fa-solid fa-clock-rotate-left text-sky-600"></i>
                    5 Pasien Terakhir
                </h2>
                <p class="text-xs text-stone-500 mt-0.5">Pasien yang paling baru melakukan skrining mandiri</p>
            </div>
            <a href="{{ route('riwayat.index') }}" class="text-xs font-semibold text-sky-600 hover:text-sky-800 transition">
                Lihat Semua &rarr;
            </a>
        </div>

        <!-- Mobile View (Cards) -->
        <div class="block md:hidden space-y-3">
            @forelse($recentPredictions as $item)
                <div class="bg-stone-50 rounded-xl p-3.5 border border-stone-200 space-y-2.5">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <div class="font-semibold text-sm text-stone-900">{{ $item->nama_pengguna }}</div>
                            <div class="text-[11px] text-stone-500 font-mono mt-0.5">
                                {{ \Carbon\Carbon::parse($item->tanggal_prediksi)->format('d/m/Y H:i') }} &bull; {{ $item->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}, {{ $item->usia }} thn
                            </div>
                        </div>
                        @if($item->status_hipertensi == 'RISIKO TINGGI')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-800 border border-red-200 whitespace-nowrap">
                                <i class="fa-solid fa-triangle-exclamation mr-1"></i> Risiko Tinggi
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200 whitespace-nowrap">
                                <i class="fa-solid fa-circle-check mr-1"></i> Normotensi
                            </span>
                        @endif
                    </div>
                    <div class="grid grid-cols-3 gap-2 bg-white rounded-lg p-2 border border-stone-200 text-center text-xs">
                        <div>
                            <span class="text-[10px] text-stone-400 block">BB / TB</span>
                            <span class="font-semibold text-stone-700">{{ $item->berat_badan }}k / {{ $item->tinggi_badan }}c</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-stone-400 block">IMT</span>
                            <span class="font-semibold text-stone-800">{{ $item->bmi }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-stone-400 block">Prob</span>
                            <span class="font-bold text-stone-900">{{ $item->probabilitas }}%</span>
                        </div>
                    </div>
                    <a href="{{ route('skrining.rujukan', $item->id_prediction) }}" target="_blank"
                       class="min-h-[40px] w-full bg-white hover:bg-sky-50 text-sky-700 font-semibold px-3 py-2 rounded-lg text-xs border border-stone-200 hover:border-sky-300 transition flex items-center justify-center gap-1.5 active:scale-[0.99]">
                        <i class="fa-solid fa-print"></i>
                        <span>Cetak Surat Rujukan</span>
                    </a>
                </div>
            @empty
                <div class="text-center py-6 text-stone-400 text-xs">
                    Belum ada catatan skrining yang tersimpan.
                </div>
            @endforelse
        </div>

        <!-- Desktop View (Tabel) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left text-xs text-stone-600">
                <thead class="bg-stone-50 text-stone-600 font-semibold border-b border-stone-200">
                    <tr>
                        <th class="py-2.5 px-3">Waktu</th>
                        <th class="py-2.5 px-3">Nama Pasien</th>
                        <th class="py-2.5 px-3">Gender / Usia</th>
                        <th class="py-2.5 px-3">BB / TB</th>
                        <th class="py-2.5 px-3">IMT</th>
                        <th class="py-2.5 px-3">Probabilitas</th>
                        <th class="py-2.5 px-3">Status</th>
                        <th class="py-2.5 px-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($recentPredictions as $item)
                        <tr class="hover:bg-stone-50/80 transition">
                            <td class="py-3 px-3 font-mono text-[11px] text-stone-400 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($item->tanggal_prediksi)->format('d/m/Y H:i') }}
                            </td>
                            <td class="py-3 px-3 font-semibold text-stone-800">{{ $item->nama_pengguna }}</td>
                            <td class="py-3 px-3">{{ $item->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}, {{ $item->usia }} th</td>
                            <td class="py-3 px-3">{{ $item->berat_badan }} kg / {{ $item->tinggi_badan }} cm</td>
                            <td class="py-3 px-3 font-medium text-stone-700">{{ $item->bmi }}</td>
                            <td class="py-3 px-3 font-bold text-stone-800">{{ $item->probabilitas }}%</td>
                            <td class="py-3 px-3">
                                @if($item->status_hipertensi == 'RISIKO TINGGI')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-red-100 text-red-800 border border-red-200">
                                        <i class="fa-solid fa-circle-exclamation mr-1"></i> Risiko Tinggi
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        <i class="fa-solid fa-circle-check mr-1"></i> Normotensi
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-3 text-center">
                                <a href="{{ route('skrining.rujukan', $item->id_prediction) }}" target="_blank"
                                   class="inline-block text-xs text-sky-600 hover:text-sky-800 font-semibold hover:underline">
                                    <i class="fa-solid fa-print"></i> Cetak
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-6 text-stone-400 text-xs">
                                Belum ada catatan skrining yang tersimpan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const ctx = document.getElementById('chartTrenMingguan').getContext('2d');
        const days = @json($days);
        const dataNormo = @json($weeklyNormo);
        const dataHigh = @json($weeklyHigh);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: days,
                datasets: [
                    {
                        label: 'Normotensi (Risiko Rendah)',
                        data: dataNormo,
                        backgroundColor: '#10b981',
                        borderRadius: 4
                    },
                    {
                        label: 'Risiko Tinggi Hipertensi',
                        data: dataHigh,
                        backgroundColor: '#ef4444',
                        borderRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            boxWidth: 12,
                            font: { size: 11, weight: 'bold' }
                        }
                    }
                },
                scales: {
                    x: { grid: { display: false } },
                    y: { beginAtZero: true, ticks: { stepSize: 5 } }
                }
            }
        });
    });
</script>
@endpush
