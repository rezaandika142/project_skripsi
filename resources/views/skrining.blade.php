@extends('layouts.app')

@section('title', 'Skrining Mandiri Hipertensi')

@section('content')
<div class="space-y-6">
    <!-- Header Banner -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 sm:p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-sky-600 font-semibold text-xs tracking-wider uppercase mb-1">
                <i class="fa-solid fa-notes-medical"></i>
                <span>Pemeriksaan Mandiri Berbasis Machine Learning</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-800 tracking-tight">Skrining Deteksi Dini Risiko Hipertensi</h1>
            <p class="text-xs sm:text-sm text-slate-600 mt-1 max-w-2xl leading-relaxed">
                Evaluasi faktor risiko fisik secara mandiri menggunakan model <strong>ExtraTrees Classifier</strong> yang dioptimalkan dengan ambang batas (threshold) <strong>81.5%</strong>.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <span class="text-xs bg-slate-100 text-slate-700 font-medium px-2.5 py-1.5 rounded-lg border border-slate-300">
                <i class="fa-solid fa-microchip text-sky-600 mr-1.5"></i>Model: {{ $activeModel->versi_model }}
            </span>
            <span class="text-xs bg-emerald-50 text-emerald-700 font-semibold px-2.5 py-1.5 rounded-lg border border-emerald-200">
                <i class="fa-solid fa-bullseye text-emerald-600 mr-1.5"></i>Akurasi: {{ number_format($activeModel->akurasi, 2) }}%
            </span>
        </div>
    </div>

    <!-- Main Grid: Form (Left) & Realtime CDSS Result (Right) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 sm:gap-6 items-start">
        
        <!-- Kolom Kiri: Formulir Input (5 Parameter Fisik) -->
        <div class="lg:col-span-6 bg-white rounded-xl shadow-sm border border-slate-200 p-4 sm:p-6 space-y-5">
            <div class="border-b border-slate-100 pb-3">
                <h2 class="font-bold text-sm sm:text-base text-slate-800 flex items-center">
                    <i class="fa-solid fa-clipboard-user text-sky-600 mr-2"></i>
                    Parameter Fisik Pasien
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Masukkan data fisik pasien secara mandiri untuk dianalisis oleh sistem.</p>
            </div>

            <form id="formSkrining" class="space-y-4" onsubmit="event.preventDefault(); jalankanSkrining();">
                <!-- Nama Pasien (Opsional) -->
                <div>
                    <label for="nama_pengguna" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Nama Pasien <span class="text-slate-400 font-normal lowercase">(opsional / anonim)</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 pointer-events-none">
                            <i class="fa-regular fa-user text-sm"></i>
                        </span>
                        <input type="text" id="nama_pengguna" name="nama_pengguna" placeholder="Contoh: Budi Santoso"
                            class="w-full pl-9 pr-3 py-2.5 text-base sm:text-sm min-h-[44px] border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 outline-none transition bg-white">
                    </div>
                </div>

                <!-- Jenis Kelamin -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Jenis Kelamin <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative flex items-center justify-center p-3.5 min-h-[48px] border border-slate-300 rounded-lg cursor-pointer hover:border-sky-500 transition active:scale-[0.98] has-[:checked]:border-sky-600 has-[:checked]:bg-sky-50/60 has-[:checked]:text-sky-900">
                            <input type="radio" name="jenis_kelamin" value="L" checked class="accent-sky-600 mr-2 w-4 h-4" onchange="hitungIMT()">
                            <span class="text-sm font-semibold flex items-center select-none">
                                <i class="fa-solid fa-mars text-blue-600 mr-1.5"></i> Laki-laki
                            </span>
                        </label>
                        <label class="relative flex items-center justify-center p-3.5 min-h-[48px] border border-slate-300 rounded-lg cursor-pointer hover:border-sky-500 transition active:scale-[0.98] has-[:checked]:border-sky-600 has-[:checked]:bg-sky-50/60 has-[:checked]:text-sky-900">
                            <input type="radio" name="jenis_kelamin" value="P" class="accent-sky-600 mr-2 w-4 h-4" onchange="hitungIMT()">
                            <span class="text-sm font-semibold flex items-center select-none">
                                <i class="fa-solid fa-venus text-pink-600 mr-1.5"></i> Perempuan
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Usia -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label for="usia" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Usia Pasien <span class="text-red-500">*</span>
                        </label>
                        <span id="usiaBadge" class="text-[11px] font-semibold text-slate-500">Kelompok: -</span>
                    </div>
                    <div class="relative">
                        <input type="number" id="usia" name="usia" min="18" max="110" placeholder="Contoh: 45" required inputmode="numeric"
                            class="w-full pl-3 pr-16 py-2.5 text-base sm:text-sm min-h-[44px] border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 outline-none transition bg-white"
                            oninput="hitungIMT()" onwheel="this.blur()">
                        <span class="absolute inset-y-1.5 right-1.5 px-2.5 flex items-center bg-slate-100/80 text-slate-400 text-xs font-semibold rounded pointer-events-none select-none tracking-wide">
                            Tahun
                        </span>
                    </div>
                </div>

                <!-- Berat Badan & Tinggi Badan (2 Kolom) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="berat_badan" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Berat Badan <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" step="0.5" id="berat_badan" name="berat_badan" min="25" max="250" placeholder="Contoh: 65" required inputmode="decimal"
                                class="w-full pl-3 pr-14 py-2.5 text-base sm:text-sm min-h-[44px] border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 outline-none transition bg-white"
                                oninput="hitungIMT()" onwheel="this.blur()">
                            <span class="absolute inset-y-1.5 right-1.5 px-2.5 flex items-center bg-slate-100/80 text-slate-400 text-xs font-semibold rounded pointer-events-none select-none tracking-wide">
                                kg
                            </span>
                        </div>
                    </div>

                    <div>
                        <label for="tinggi_badan" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Tinggi Badan <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" step="0.5" id="tinggi_badan" name="tinggi_badan" min="100" max="230" placeholder="Contoh: 165" required inputmode="decimal"
                                class="w-full pl-3 pr-14 py-2.5 text-base sm:text-sm min-h-[44px] border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 outline-none transition bg-white"
                                oninput="hitungIMT()" onwheel="this.blur()">
                            <span class="absolute inset-y-1.5 right-1.5 px-2.5 flex items-center bg-slate-100/80 text-slate-400 text-xs font-semibold rounded pointer-events-none select-none tracking-wide">
                                cm
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Kalkulasi Otomatis IMT / BMI (Live Readonly Box) -->
                <div class="bg-slate-50 border border-slate-200 rounded-lg p-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <div class="text-[11px] font-bold uppercase tracking-wider text-slate-500">
                            Indeks Massa Tubuh (IMT / BMI)
                        </div>
                        <div class="flex items-baseline space-x-2 mt-0.5">
                            <span id="bmiValue" class="text-xl font-extrabold text-slate-800">-</span>
                            <span class="text-xs text-slate-500">kg/m&sup2;</span>
                        </div>
                    </div>
                    <div class="sm:text-right">
                        <span id="bmiCategoryBadge" class="inline-block px-2.5 py-1 text-xs font-bold rounded-md bg-slate-100 text-slate-500 border border-slate-200">
                            Belum Dihitung
                        </span>
                    </div>
                </div>

                <!-- Tombol Submit Analisis -->
                <div class="pt-2">
                    <button type="submit" id="btnAnalisis"
                        class="w-full min-h-[48px] bg-[#0284c7] hover:bg-sky-600 active:scale-[0.99] text-white font-bold py-3.5 px-4 rounded-lg shadow-sm hover:shadow transition flex items-center justify-center space-x-2 text-sm sm:text-base">
                        <i class="fa-solid fa-heart-pulse text-base" id="btnIcon"></i>
                        <span id="btnText">Analisis Risiko Hipertensi</span>
                    </button>
                    <p class="text-[11px] text-slate-500 text-center mt-2 leading-relaxed">
                        Eksekusi inferensi berjalan secara langsung di browser melalui WebAssembly (WASM).
                    </p>
                </div>
            </form>
        </div>

        <!-- Kolom Kanan: Hasil Analisis CDSS (Realtime & Interaktif) -->
        <div class="lg:col-span-6 space-y-5">
            
            <!-- Placeholder Awal (Sebelum Pengujian) -->
            <div id="hasilPlaceholder" class="bg-white rounded-xl shadow-sm border border-slate-200 p-8 text-center space-y-3">
                <div class="w-16 h-16 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto text-2xl">
                    <i class="fa-solid fa-notes-medical"></i>
                </div>
                <h3 class="font-bold text-base text-slate-700">Menunggu Data Pasien</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto leading-relaxed">
                    Lengkapi parameter fisik pada formulir di sebelah kiri dan klik tombol <strong>Analisis Risiko Hipertensi</strong> untuk melihat evaluasi risiko berbasis model ExtraTrees.
                </p>
            </div>

            <!-- Card Hasil Skrining (Tampil Setelah Model Berjalan) -->
            <div id="hasilContainer" class="hidden space-y-4">
                
                <!-- Status Utama -->
                <div id="statusCard" class="rounded-xl p-5 border shadow-sm transition-all">
                    <div class="flex items-start justify-between">
                        <div>
                            <span id="badgeKategori" class="inline-block text-xs uppercase tracking-wider font-extrabold px-3 py-1 rounded-md mb-2">
                                -
                            </span>
                            <h3 id="statusTitle" class="text-xl font-black tracking-tight">
                                -
                            </h3>
                            <p id="statusSubtitle" class="text-xs mt-1 leading-relaxed">
                                -
                            </p>
                        </div>
                        <div id="statusIconBox" class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl flex-shrink-0 shadow-sm">
                            <i class="fa-solid fa-heart-pulse"></i>
                        </div>
                    </div>

                    <!-- Progress Bar Probabilitas vs Threshold -->
                    <div class="mt-5 pt-4 border-t border-slate-200/60 space-y-2">
                        <div class="flex justify-between items-center text-xs">
                            <span class="font-bold text-slate-700">Probabilitas Hipertensi:</span>
                            <span id="probText" class="font-black text-sm text-slate-900">0.0%</span>
                        </div>
                        
                        <div class="relative w-full bg-slate-200 rounded-full h-3.5 overflow-hidden shadow-inner">
                            <div id="progressBar" class="h-full rounded-full transition-all duration-700 ease-out" style="width: 0%;"></div>
                            <!-- Marker Garis Threshold 81.5% -->
                            <div class="absolute top-0 bottom-0 left-[81.5%] w-0.5 bg-slate-900 z-10" title="Threshold Optimal: 81.5%"></div>
                        </div>

                        <div class="flex justify-between items-center text-[11px] text-slate-500">
                            <span>0% (Normotensi)</span>
                            <span class="font-bold text-slate-700"><i class="fa-solid fa-caret-up text-slate-800 mr-0.5"></i>Ambang Batas: 81.5%</span>
                            <span>100%</span>
                        </div>
                    </div>
                </div>

                <!-- Rekomendasi Klinis RSI Kendal -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 space-y-3">
                    <h4 class="font-bold text-xs uppercase tracking-wider text-slate-700 flex items-center">
                        <i class="fa-solid fa-user-doctor text-sky-600 mr-2"></i>
                        Rekomendasi Tindakan Medis (CDSS)
                    </h4>
                    <div id="rekomendasiText" class="text-xs text-slate-600 leading-relaxed space-y-2">
                        <!-- Disuntikkan via JS -->
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="pt-3 border-t border-slate-100 flex flex-col sm:flex-row gap-2.5">
                        <button type="button" id="btnCetakRujukan" onclick="unduhAtauCetakLaporan()"
                            class="flex-1 min-h-[44px] bg-slate-900 hover:bg-slate-800 text-white font-bold py-3 px-4 rounded-lg text-xs sm:text-sm text-center shadow-sm transition flex items-center justify-center space-x-1.5 active:scale-[0.99] cursor-pointer">
                            <i class="fa-solid fa-file-pdf text-red-400"></i>
                            <span>Cetak Surat Rujukan / Unduh PDF</span>
                        </button>
                        <button type="button" onclick="window.location.reload()"
                            class="min-h-[44px] bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-3 px-4 rounded-lg text-xs sm:text-sm transition active:scale-[0.99]">
                            Skrining Ulang
                        </button>
                    </div>
                </div>

                <!-- Penjelasan 18 Variabel yang Dihitung Secara Otomatis -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
                    <details class="text-xs text-slate-600 cursor-pointer">
                        <summary class="font-bold text-slate-700 hover:text-sky-600 select-none py-1">
                            <i class="fa-solid fa-circle-info mr-1.5 text-sky-500"></i>
                            Rincian 18 Fitur Model (Calculated Behind the Scenes)
                        </summary>
                        <div class="mt-3 pt-3 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-2 gap-2 text-[11px] font-mono" id="featureDebugList">
                            <!-- Diisi via JS -->
                        </div>
                    </details>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    // State Penyimpanan Sesi Inferensi ONNX
    let ortSession = null;
    let savedPredictionId = null;
    let lastScreeningData = null;

    // Nilai Skalasi Standar dari Notebook Penelitian (with_mean=False)
    const SCALERS = {
        USIA: 14.872762,
        BB: 11.746430,
        TB: 7.992533,
        BMI: 4.229364,
        WT_HT_RATIO: 0.0676955,
        BSA: 0.1786556,
        RISK_SCORE: 0.9685736
    };

    // Ambang Batas Optimal (Tuning Threshold)
    const OPTIMAL_THRESHOLD = 0.815;

    // Inisialisasi ONNX Runtime Web saat halaman siap
    document.addEventListener('DOMContentLoaded', async () => {
        hitungIMT();
        await inisialisasiModelONNX();
    });

    async function inisialisasiModelONNX() {
        try {
            console.log('Memuat model ONNX: /models/model_extratrees.onnx...');
            ortSession = await ort.InferenceSession.create('/models/model_extratrees.onnx', {
                executionProviders: ['wasm']
            });
            console.log('Model ONNX ExtraTrees berhasil dimuat ke memori browser.');
        } catch (err) {
            console.error('Gagal memuat model ONNX:', err);
        }
    }

    // Fungsi Hitung IMT & Kategori
    function hitungIMT() {
        const bbInput = document.getElementById('berat_badan').value.trim();
        const tbInput = document.getElementById('tinggi_badan').value.trim();
        const usiaInput = document.getElementById('usia').value.trim();

        const usia = parseInt(usiaInput) || 0;
        const bb = parseFloat(bbInput) || 0;
        const tb = parseFloat(tbInput) || 0;

        // Label Kelompok Usia
        let ageGroup = '-';
        if (usia >= 18 && usia <= 30) ageGroup = 'Dewasa Muda (18-30 thn)';
        else if (usia >= 31 && usia <= 45) ageGroup = 'Dewasa Tengah (31-45 thn)';
        else if (usia >= 46 && usia <= 60) ageGroup = 'Pra-Lansia (46-60 thn)';
        else if (usia > 60) ageGroup = 'Lansia (> 60 thn)';
        document.getElementById('usiaBadge').innerText = 'Kelompok: ' + ageGroup;

        const bmiValEl = document.getElementById('bmiValue');
        const badge = document.getElementById('bmiCategoryBadge');

        if (!bbInput || !tbInput || bb <= 0 || tb <= 0) {
            bmiValEl.innerText = '-';
            badge.innerText = 'Belum Dihitung';
            badge.className = 'inline-block px-2.5 py-1 text-xs font-bold rounded-md bg-slate-100 text-slate-500 border border-slate-200';
            return;
        }

        const tbMeter = tb / 100.0;
        const bmi = bb / (tbMeter * tbMeter);
        bmiValEl.innerText = bmi.toFixed(2);

        // Kategori BMI Standar Asia Pasifik (Sesuai Skripsi Bab III)
        if (bmi < 18.5) {
            badge.innerText = 'Underweight (< 18.5)';
            badge.className = 'inline-block px-2.5 py-1 text-xs font-bold rounded-md bg-blue-100 text-blue-800 border border-blue-200';
        } else if (bmi < 23.0) {
            badge.innerText = 'Normal (18.5 - 22.9)';
            badge.className = 'inline-block px-2.5 py-1 text-xs font-bold rounded-md bg-emerald-100 text-emerald-800 border border-emerald-200';
        } else if (bmi < 25.0) {
            badge.innerText = 'Overweight (23 - 24.9)';
            badge.className = 'inline-block px-2.5 py-1 text-xs font-bold rounded-md bg-yellow-100 text-yellow-800 border border-yellow-200';
        } else if (bmi < 30.0) {
            badge.innerText = 'Obesitas I (25 - 29.9)';
            badge.className = 'inline-block px-2.5 py-1 text-xs font-bold rounded-md bg-orange-100 text-orange-800 border border-orange-200';
        } else {
            badge.innerText = 'Obesitas II (≥ 30.0)';
            badge.className = 'inline-block px-2.5 py-1 text-xs font-bold rounded-md bg-red-100 text-red-800 border border-red-200';
        }
    }

    // Eksekusi Inferensi Model ONNX
    async function jalankanSkrining() {
        const btn = document.getElementById('btnAnalisis');
        const btnText = document.getElementById('btnText');
        const btnIcon = document.getElementById('btnIcon');

        // Validasi input
        const nama = document.getElementById('nama_pengguna').value.trim() || 'Pasien Mandiri';
        const jk = document.querySelector('input[name="jenis_kelamin"]:checked').value;
        const usia = parseFloat(document.getElementById('usia').value);
        const bb = parseFloat(document.getElementById('berat_badan').value);
        const tb = parseFloat(document.getElementById('tinggi_badan').value);

        if (!usia || !bb || !tb) {
            alert('Mohon isi usia pasien, berat badan, dan tinggi badan terlebih dahulu.');
            return;
        }

        btn.disabled = true;
        btnText.innerText = 'Memproses Model...';
        btnIcon.className = 'fa-solid fa-spinner fa-spin text-base';

        try {
            if (!ortSession) {
                await inisialisasiModelONNX();
            }

            // 1. Rekayasa Fitur Otomatis di Client-Side (18 Fitur)
            const tbMeter = tb / 100.0;
            const bmi = bb / (tbMeter * tbMeter);
            const wt_ht = bb / tb;
            const bsa = Math.sqrt((bb * tb) / 3600.0);
            const risk_score = (usia >= 40 ? 1 : 0) + (usia >= 55 ? 1 : 0) + (bmi >= 25 ? 1 : 0) + (bmi >= 30 ? 1 : 0);

            // 7 Fitur Numerik diskalakan (with_mean=False)
            const f_USIA = usia / SCALERS.USIA;
            const f_BB = bb / SCALERS.BB;
            const f_TB = tb / SCALERS.TB;
            const f_BMI = bmi / SCALERS.BMI;
            const f_WT_HT = wt_ht / SCALERS.WT_HT_RATIO;
            const f_BSA = bsa / SCALERS.BSA;
            const f_RISK = risk_score / SCALERS.RISK_SCORE;

            // 11 Fitur Kategorikal One-Hot Encoded
            const f_JK_L = jk === 'L' ? 1.0 : 0.0;
            const f_JK_P = jk === 'P' ? 1.0 : 0.0;

            const f_AGE_18_30 = (usia >= 18 && usia <= 30) ? 1.0 : 0.0;
            const f_AGE_31_45 = (usia >= 31 && usia <= 45) ? 1.0 : 0.0;
            const f_AGE_46_60 = (usia >= 46 && usia <= 60) ? 1.0 : 0.0;
            const f_AGE_60_UP = (usia > 60) ? 1.0 : 0.0;

            const f_BMI_NORMAL = (bmi >= 18.5 && bmi < 23.0) ? 1.0 : 0.0;
            const f_BMI_OBESE1 = (bmi >= 25.0 && bmi < 30.0) ? 1.0 : 0.0;
            const f_BMI_OBESE2 = (bmi >= 30.0) ? 1.0 : 0.0;
            const f_BMI_OVER   = (bmi >= 23.0 && bmi < 25.0) ? 1.0 : 0.0;
            const f_BMI_UNDER  = (bmi < 18.5) ? 1.0 : 0.0;

            // Susunan Vektor 18 Fitur (Sesuai Urutan Transformasi Preprocessor)
            const inputVector = new Float32Array([
                f_USIA,
                f_BB,
                f_TB,
                f_BMI,
                f_WT_HT,
                f_BSA,
                f_RISK,
                f_JK_L,
                f_JK_P,
                f_AGE_18_30,
                f_AGE_31_45,
                f_AGE_46_60,
                f_AGE_60_UP,
                f_BMI_NORMAL,
                f_BMI_OBESE1,
                f_BMI_OBESE2,
                f_BMI_OVER,
                f_BMI_UNDER
            ]);

            // Debug list fitur di UI
            renderDebugList({
                'USIA (Scaled)': f_USIA.toFixed(4),
                'BB (Scaled)': f_BB.toFixed(4),
                'TB (Scaled)': f_TB.toFixed(4),
                'BMI (Scaled)': f_BMI.toFixed(4),
                'WT_HT Ratio': f_WT_HT.toFixed(4),
                'BSA (m²)': f_BSA.toFixed(4),
                'Risk Score': f_RISK.toFixed(4),
                'Gender': jk === 'L' ? 'Laki-laki' : 'Perempuan',
                'Kelompok Usia': usia > 60 ? '>60' : (usia >= 46 ? '46-60' : (usia >= 31 ? '31-45' : '18-30')),
                'Kategori BMI': bmi >= 30 ? 'Obese II' : (bmi >= 25 ? 'Obese I' : (bmi >= 23 ? 'Overweight' : (bmi >= 18.5 ? 'Normal' : 'Underweight')))
            });

            // 2. Eksekusi Inferensi ONNX
            const tensor = new ort.Tensor('float32', inputVector, [1, 18]);
            const results = await ortSession.run({ float_input: tensor });

            // Ambil Probabilitas Kelas 1 (Hipertensi)
            let probHipertensi = 0.0;
            if (results.probabilities && results.probabilities.data) {
                probHipertensi = results.probabilities.data[1];
            } else if (results.output_probability && results.output_probability.data) {
                probHipertensi = results.output_probability.data[1];
            } else {
                // Fallback jika hanya label biner
                probHipertensi = results.label.data[0] === 1 ? 0.85 : 0.15;
            }

            const persenProb = (probHipertensi * 100).toFixed(1);
            const isHighRisk = probHipertensi >= OPTIMAL_THRESHOLD;
            const statusString = isHighRisk ? 'RISIKO TINGGI' : 'NORMOTENSI';

            // Simpan data skrining saat ini untuk keperluan cetak / download PDF
            lastScreeningData = {
                nama: nama,
                usia: usia,
                jk: jk,
                bb: bb,
                tb: tb,
                bmi: bmi.toFixed(2),
                status: statusString,
                prob: persenProb
            };

            // 3. Tampilkan Hasil pada Antarmuka (UI)
            tampilkanHasilUI({
                isHighRisk,
                persenProb,
                probHipertensi,
                nama,
                usia,
                bmi: bmi.toFixed(2)
            });

            // 4. Simpan Otomatis Hasil ke Database Server via AJAX
            await simpanHasilKeDatabase({
                nama_pengguna: nama,
                usia,
                jenis_kelamin: jk,
                berat_badan: bb,
                tinggi_badan: tb,
                bmi: parseFloat(bmi.toFixed(2)),
                status_hipertensi: statusString,
                probabilitas: parseFloat(persenProb)
            });

        } catch (err) {
            console.error('Terjadi kesalahan saat inferensi:', err);
            alert('Gagal melakukan inferensi ONNX: ' + err.message);
        } finally {
            btn.disabled = false;
            btnText.innerText = 'Analisis Risiko Hipertensi';
            btnIcon.className = 'fa-solid fa-heart-pulse text-base';
        }
    }

    function tampilkanHasilUI(data) {
        document.getElementById('hasilPlaceholder').classList.add('hidden');
        document.getElementById('hasilContainer').classList.remove('hidden');

        const statusCard = document.getElementById('statusCard');
        const badgeKategori = document.getElementById('badgeKategori');
        const statusTitle = document.getElementById('statusTitle');
        const statusSubtitle = document.getElementById('statusSubtitle');
        const statusIconBox = document.getElementById('statusIconBox');
        const progressBar = document.getElementById('progressBar');
        const probText = document.getElementById('probText');
        const rekomendasiText = document.getElementById('rekomendasiText');

        probText.innerText = data.persenProb + '%';
        progressBar.style.width = Math.min(data.persenProb, 100) + '%';

        if (data.isHighRisk) {
            statusCard.className = 'rounded-xl p-5 border shadow-sm transition-all bg-red-50/70 border-red-200 text-red-950';
            badgeKategori.className = 'inline-block text-xs uppercase tracking-wider font-extrabold px-3 py-1 rounded-md mb-2 bg-red-600 text-white';
            badgeKategori.innerText = 'PERHATIAN MEDIS';
            statusTitle.innerText = 'TERDETEKSI RISIKO TINGGI HIPERTENSI';
            statusSubtitle.innerText = `Berdasarkan evaluasi model, probabilitas risiko pasien (${data.persenProb}%) melampaui ambang batas klinis 81.5%.`;
            statusIconBox.className = 'w-12 h-12 rounded-xl flex items-center justify-center text-2xl flex-shrink-0 shadow-sm bg-red-600 text-white';
            statusIconBox.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i>';
            progressBar.className = 'h-full rounded-full transition-all duration-700 ease-out bg-red-600';

            rekomendasiText.innerHTML = `
                <div class="p-3 bg-red-50 rounded-lg border border-red-200 text-red-900 font-medium">
                    <i class="fa-solid fa-circle-exclamation text-red-600 mr-1.5"></i>
                    <strong>Peringatan Klinis:</strong> Segera lakukan konfirmasi tekanan darah menggunakan sfigmomanometer oleh tenaga medis profesional di Poliklinik RSI Kendal.
                </div>
                <ul class="list-disc list-inside space-y-1 text-slate-700 pt-1">
                    <li>Kurangi konsumsi natrium/garam dapur (maksimal &lt; 2 gram atau 1 sendok teh per hari).</li>
                    <li>Lakukan pengukuran tensi pagi dan malam hari secara berkala (Home Blood Pressure Monitoring).</li>
                    <li>Hindari kebiasaan merokok dan batasi konsumsi minuman berkafein atau berenergi.</li>
                    <li>Cetak bukti skrining mandiri di bawah ini untuk dibawa saat konsultasi dokter.</li>
                </ul>
            `;
        } else {
            statusCard.className = 'rounded-xl p-5 border shadow-sm transition-all bg-emerald-50/70 border-emerald-200 text-emerald-950';
            badgeKategori.className = 'inline-block text-xs uppercase tracking-wider font-extrabold px-3 py-1 rounded-md mb-2 bg-emerald-600 text-white';
            badgeKategori.innerText = 'HASIL NORMAL';
            statusTitle.innerText = 'NORMOTENSI (RISIKO RENDAH)';
            statusSubtitle.innerText = `Probabilitas risiko pasien (${data.persenProb}%) berada di bawah ambang batas klinis 81.5%.`;
            statusIconBox.className = 'w-12 h-12 rounded-xl flex items-center justify-center text-2xl flex-shrink-0 shadow-sm bg-emerald-600 text-white';
            statusIconBox.innerHTML = '<i class="fa-solid fa-circle-check"></i>';
            progressBar.className = 'h-full rounded-full transition-all duration-700 ease-out bg-emerald-600';

            rekomendasiText.innerHTML = `
                <div class="p-3 bg-emerald-50 rounded-lg border border-emerald-200 text-emerald-900 font-medium">
                    <i class="fa-solid fa-circle-check text-emerald-600 mr-1.5"></i>
                    <strong>Kondisi Baik:</strong> Parameter fisik menunjukkan risiko hipertensi rendah.
                </div>
                <ul class="list-disc list-inside space-y-1 text-slate-700 pt-1">
                    <li>Pertahankan berat badan ideal dengan pola makan gizi seimbang (kaya serat, sayur, dan buah).</li>
                    <li>Lakukan aktivitas fisik aerobik minimal 150 menit per minggu (jalan santai, bersepeda, senam).</li>
                    <li>Cukupi waktu tidur 7 - 8 jam setiap malam untuk menjaga elastisitas pembuluh darah.</li>
                    <li>Lakukan pemeriksaan skrining mandiri secara rutin setiap 3 - 6 bulan sekali.</li>
                </ul>
            `;
        }
    }

    async function simpanHasilKeDatabase(payload) {
        try {
            const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
            const csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';
            const res = await fetch('{{ route("skrining.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(payload)
            });
            if (res.ok) {
                const resData = await res.json();
                if (resData.success && resData.id_prediction) {
                    savedPredictionId = resData.id_prediction;
                }
            }
        } catch (err) {
            console.warn('Gagal menyimpan riwayat ke server:', err);
        }
    }

    function unduhAtauCetakLaporan() {
        if (!lastScreeningData) {
            alert('Silakan lakukan analisis skrining terlebih dahulu.');
            return;
        }

        const params = new URLSearchParams({
            nama: lastScreeningData.nama,
            usia: lastScreeningData.usia,
            jk: lastScreeningData.jk,
            bb: lastScreeningData.bb,
            tb: lastScreeningData.tb,
            bmi: lastScreeningData.bmi,
            status: lastScreeningData.status,
            prob: lastScreeningData.prob,
            autoPrint: '1'
        }).toString();

        let targetUrl = '';
        if (savedPredictionId) {
            targetUrl = '/skrining/rujukan/' + savedPredictionId + '?' + params;
        } else {
            targetUrl = '/skrining/rujukan/preview?' + params;
        }

        window.open(targetUrl, '_blank');
    }

    function renderDebugList(obj) {
        const box = document.getElementById('featureDebugList');
        box.innerHTML = '';
        for (const [k, v] of Object.entries(obj)) {
            const row = document.createElement('div');
            row.className = 'flex justify-between py-0.5 border-b border-slate-100';
            row.innerHTML = `<span class="text-slate-500">${k}:</span><span class="font-bold text-slate-800">${v}</span>`;
            box.appendChild(row);
        }
    }
</script>
@endpush
