@extends('layouts.app')

@section('title', 'Dasbor Monitoring Skrining Hipertensi')

@section('content')
<div class="space-y-6">

    <!-- Top Welcome & System Status Banner -->
    <div class="bg-gradient-to-r from-[#0f172a] via-[#1e293b] to-[#0f172a] rounded-xl shadow-md p-4 sm:p-6 text-white border border-slate-700/60 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-sky-400 font-bold text-xs uppercase tracking-widest mb-1.5">
                <i class="fa-solid fa-hospital-user"></i>
                <span>Sistem Monitoring Skrining Mandiri &bull; RSI Kendal</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-black tracking-tight">Dasbor Pemantauan Risiko Hipertensi</h1>
            <p class="text-xs text-slate-300 mt-1 max-w-2xl leading-relaxed">
                Pemantauan data skrining masyarakat dan evaluasi klinis secara terpusat menggunakan model <em>ExtraTrees Classifier</em> berkinerja tinggi.
            </p>
        </div>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 w-full md:w-auto">
            <a href="{{ route('skrining.index') }}" class="min-h-[44px] bg-sky-500 hover:bg-sky-400 text-white font-bold px-4 py-2.5 rounded-lg text-xs shadow-sm transition flex items-center justify-center space-x-2 active:scale-95">
                <i class="fa-solid fa-plus-circle"></i>
                <span>Skrining Pasien Baru</span>
            </a>
            <a href="{{ route('riwayat.export') }}" class="min-h-[44px] bg-slate-800 hover:bg-slate-700 text-slate-200 font-semibold px-4 py-2.5 rounded-lg text-xs border border-slate-600 transition flex items-center justify-center space-x-2 active:scale-95">
                <i class="fa-solid fa-file-csv"></i>
                <span>Ekspor CSV</span>
            </a>
        </div>
    </div>

    <!-- 4 KPI Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Card 1: Total Skrining -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 flex items-center justify-between">
            <div>
                <div class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Skrining</div>
                <div class="text-2xl font-black text-slate-800 mt-1">{{ number_format($totalPredictions) }}</div>
                <div class="text-[11px] text-slate-500 mt-1 flex items-center">
                    <span class="text-sky-600 font-semibold mr-1">Basis Data:</span> 1.553 rekam medis
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-xl flex-shrink-0">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>

        <!-- Card 2: Risiko Tinggi -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 flex items-center justify-between">
            <div>
                <div class="text-xs font-bold text-slate-500 uppercase tracking-wider">Risiko Tinggi</div>
                <div class="text-2xl font-black text-red-600 mt-1">{{ number_format($totalRisikoTinggi) }}</div>
                <div class="text-[11px] text-slate-500 mt-1">
                    @php
                        $pctHT = $totalPredictions > 0 ? round(($totalRisikoTinggi / $totalPredictions) * 100, 1) : 23.4;
                    @endphp
                    <span class="font-bold text-red-600">{{ $pctHT }}%</span> dari total populasi skrining
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-red-50 text-red-600 flex items-center justify-center text-xl flex-shrink-0">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
        </div>

        <!-- Card 3: Normotensi -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 flex items-center justify-between">
            <div>
                <div class="text-xs font-bold text-slate-500 uppercase tracking-wider">Normotensi</div>
                <div class="text-2xl font-black text-emerald-600 mt-1">{{ number_format($totalNormotensi) }}</div>
                <div class="text-[11px] text-slate-500 mt-1">
                    @php
                        $pctNormo = $totalPredictions > 0 ? round(($totalNormotensi / $totalPredictions) * 100, 1) : 76.6;
                    @endphp
                    <span class="font-bold text-emerald-600">{{ $pctNormo }}%</span> kondisi risiko rendah
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl flex-shrink-0">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>

        <!-- Card 4: Model Kinerja -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 flex items-center justify-between">
            <div>
                <div class="text-xs font-bold text-slate-500 uppercase tracking-wider">Akurasi Model</div>
                <div class="text-2xl font-black text-slate-800 mt-1">{{ number_format($activeModel->akurasi, 2) }}%</div>
                <div class="text-[11px] text-slate-500 mt-1">
                    Threshold: <span class="font-bold text-slate-700">81.5%</span> (ExtraTrees v1.2)
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl flex-shrink-0">
                <i class="fa-solid fa-brain"></i>
            </div>
        </div>

    </div>

    <!-- Charts Row: Tren Mingguan & Distribusi IMT -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Kolom 1 (8 span): Grafik Tren Skrining Mingguan -->
        <div class="lg:col-span-8 bg-white rounded-xl shadow-sm border border-slate-200 p-5 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h2 class="font-bold text-sm text-slate-800 flex items-center">
                        <i class="fa-solid fa-chart-area text-sky-600 mr-2"></i>
                        Tren Skrining Pasien Mingguan
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">Jumlah pasien terperiksa per hari (Senin s/d Minggu)</p>
                </div>
                <span class="text-[11px] bg-slate-100 text-slate-600 font-semibold px-2.5 py-1 rounded">
                    Minggu Ini
                </span>
            </div>
            
            <div class="h-64">
                <canvas id="chartTrenMingguan"></canvas>
            </div>
        </div>

        <!-- Kolom 2 (4 span): Distribusi Status IMT (BMI) -->
        <div class="lg:col-span-4 bg-white rounded-xl shadow-sm border border-slate-200 p-5 space-y-4">
            <div class="border-b border-slate-100 pb-3">
                <h2 class="font-bold text-sm text-slate-800 flex items-center">
                    <i class="fa-solid fa-weight-scale text-sky-600 mr-2"></i>
                    Distribusi IMT (BMI)
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Proporsi indeks massa tubuh populasi</p>
            </div>

            <!-- Progress Bars IMT -->
            <div class="space-y-3.5 pt-1">
                <!-- Underweight -->
                <div>
                    <div class="flex justify-between text-xs font-semibold mb-1">
                        <span class="text-slate-600">Underweight (&lt; 18.5)</span>
                        <span class="text-slate-800">{{ $bmiStats['underweight'] }}%</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                        <div class="bg-blue-500 h-2.5 rounded-full" style="width: {{ $bmiStats['underweight'] }}%"></div>
                    </div>
                </div>

                <!-- Normal -->
                <div>
                    <div class="flex justify-between text-xs font-semibold mb-1">
                        <span class="text-slate-600">Normal (18.5 - 22.9)</span>
                        <span class="text-slate-800">{{ $bmiStats['normal'] }}%</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                        <div class="bg-emerald-500 h-2.5 rounded-full" style="width: {{ $bmiStats['normal'] }}%"></div>
                    </div>
                </div>

                <!-- Overweight -->
                <div>
                    <div class="flex justify-between text-xs font-semibold mb-1">
                        <span class="text-slate-600">Overweight (23 - 24.9)</span>
                        <span class="text-slate-800">{{ $bmiStats['overweight'] }}%</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                        <div class="bg-amber-500 h-2.5 rounded-full" style="width: {{ $bmiStats['overweight'] }}%"></div>
                    </div>
                </div>

                <!-- Obesitas -->
                <div>
                    <div class="flex justify-between text-xs font-semibold mb-1">
                        <span class="text-slate-600">Obesitas (&ge; 25.0)</span>
                        <span class="text-slate-800">{{ $bmiStats['obese'] }}%</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                        <div class="bg-red-500 h-2.5 rounded-full" style="width: {{ $bmiStats['obese'] }}%"></div>
                    </div>
                </div>
            </div>

            <div class="pt-2 border-t border-slate-100 text-[11px] text-slate-500">
                <i class="fa-solid fa-circle-info mr-1 text-sky-500"></i>
                Standar klasifikasi IMT mengacu pada kriteria Asia-Pasifik (WHO WPRO).
            </div>
        </div>

    </div>

    <!-- Aktivitas Skrining Terbaru (Tabel Ringkas) -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
                <h2 class="font-bold text-sm text-slate-800 flex items-center">
                    <i class="fa-solid fa-clock-rotate-left text-sky-600 mr-2"></i>
                    Aktivitas Skrining Terbaru
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">5 pasien terakhir yang melakukan pemeriksaan mandiri</p>
            </div>
            <a href="{{ route('riwayat.index') }}" class="text-xs font-bold text-sky-600 hover:text-sky-800 transition">
                Lihat Semua Riwayat &rarr;
            </a>
        </div>

        <!-- Mobile View (Cards - Tampil di Layar Kecil < md) -->
        <div class="block md:hidden space-y-3">
            @forelse($recentPredictions as $item)
                <div class="bg-slate-50 rounded-xl p-3.5 border border-slate-200/80 space-y-2.5">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <div class="font-bold text-sm text-slate-900">{{ $item->nama_pengguna }}</div>
                            <div class="text-[11px] text-slate-500 font-mono mt-0.5">
                                {{ \Carbon\Carbon::parse($item->tanggal_prediksi)->format('d/m/Y H:i') }} &bull; {{ $item->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}, {{ $item->usia }} thn
                            </div>
                        </div>
                        <div>
                            @if($item->status_hipertensi == 'RISIKO TINGGI')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-800 border border-red-200">
                                    <i class="fa-solid fa-triangle-exclamation mr-1"></i> Risiko Tinggi
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                    <i class="fa-solid fa-circle-check mr-1"></i> Normotensi
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-2 bg-white rounded-lg p-2 border border-slate-200 text-center text-xs">
                        <div>
                            <span class="text-[10px] text-slate-400 block uppercase">BB / TB</span>
                            <span class="font-bold text-slate-700">{{ $item->berat_badan }}k / {{ $item->tinggi_badan }}c</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-400 block uppercase">IMT</span>
                            <span class="font-bold text-slate-800">{{ $item->bmi }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-400 block uppercase">Prob</span>
                            <span class="font-black text-slate-900">{{ $item->probabilitas }}%</span>
                        </div>
                    </div>

                    <div class="pt-1">
                        <a href="{{ route('skrining.rujukan', $item->id_prediction) }}" target="_blank"
                            class="min-h-[40px] w-full bg-white hover:bg-sky-50 text-sky-700 hover:text-sky-800 font-bold px-3 py-2 rounded-lg text-xs border border-slate-300 hover:border-sky-300 transition flex items-center justify-center space-x-1.5 active:scale-[0.99]">
                            <i class="fa-solid fa-print"></i>
                            <span>Cetak Surat Rujukan</span>
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-center py-6 text-slate-400 text-xs">
                    Belum ada catatan skrining yang tersimpan.
                </div>
            @endforelse
        </div>

        <!-- Desktop View (Tabel - Tampil di Layar Sedang & Lebar >= md) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 uppercase tracking-wider font-bold border-b border-slate-200">
                    <tr>
                        <th class="py-2.5 px-3">Waktu</th>
                        <th class="py-2.5 px-3">Nama Pasien</th>
                        <th class="py-2.5 px-3">Gender / Usia</th>
                        <th class="py-2.5 px-3">BB / TB</th>
                        <th class="py-2.5 px-3">IMT</th>
                        <th class="py-2.5 px-3">Probabilitas</th>
                        <th class="py-2.5 px-3">Status Risiko</th>
                        <th class="py-2.5 px-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentPredictions as $item)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3 px-3 font-mono text-[11px] text-slate-500">
                                {{ \Carbon\Carbon::parse($item->tanggal_prediksi)->format('d/m/Y H:i') }}
                            </td>
                            <td class="py-3 px-3 font-bold text-slate-800">
                                {{ $item->nama_pengguna }}
                            </td>
                            <td class="py-3 px-3">
                                {{ $item->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}, {{ $item->usia }} th
                            </td>
                            <td class="py-3 px-3">
                                {{ $item->berat_badan }} kg / {{ $item->tinggi_badan }} cm
                            </td>
                            <td class="py-3 px-3 font-semibold text-slate-700">
                                {{ $item->bmi }}
                            </td>
                            <td class="py-3 px-3 font-bold">
                                {{ $item->probabilitas }}%
                            </td>
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
                                    class="inline-block text-xs text-sky-600 hover:text-sky-800 font-bold hover:underline" title="Cetak Surat Rujukan">
                                    <i class="fa-solid fa-print"></i> Cetak
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-6 text-slate-400 text-xs">
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
                    x: {
                        grid: { display: false }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 5 }
                    }
                }
            }
        });
    });
</script>
@endpush
