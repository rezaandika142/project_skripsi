<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Pengantar Rujukan Skrining Hipertensi - {{ $prediction->nama_pengguna }}</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; }
            .print-border { border: 1px solid #cbd5e1 !important; }
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen py-4 sm:py-8 text-slate-800 antialiased px-2 sm:px-4">

    <!-- Action Toolbar (No Print) -->
    <div class="max-w-3xl mx-auto mb-4 flex items-center justify-between no-print">
        <a href="{{ route('riwayat.index') }}" class="min-h-[40px] text-xs font-semibold text-slate-600 hover:text-slate-900 flex items-center space-x-1.5 px-2 py-1 rounded">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Riwayat</span>
        </a>
        <div class="flex items-center space-x-2">
            <button onclick="window.print()" class="min-h-[42px] bg-sky-600 hover:bg-sky-500 text-white font-bold px-4 py-2 rounded-lg text-xs shadow transition flex items-center space-x-1.5 active:scale-95">
                <i class="fa-solid fa-print"></i>
                <span>Cetak Lembar Pengantar</span>
            </button>
        </div>
    </div>

    <!-- Printable Paper Sheet (A4 size styled) -->
    <div class="max-w-3xl mx-auto bg-white p-4 sm:p-8 md:p-12 rounded-xl shadow-md border border-slate-200 print-border">
        
        <!-- Hospital Header / Kop Surat -->
        <div class="border-b-2 border-slate-900 pb-4 mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center space-x-3 sm:space-x-4">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-xl bg-slate-900 text-white flex items-center justify-center text-2xl sm:text-3xl font-black flex-shrink-0">
                        <i class="fa-solid fa-hospital"></i>
                    </div>
                    <div>
                        <h1 class="text-base sm:text-xl font-black tracking-tight text-slate-900">RUMAH SAKIT ISLAM KENDAL</h1>
                        <p class="text-[11px] sm:text-xs text-slate-600">Pelayanan Kesehatan Terpadu, Islami, dan Berstandar Mutu</p>
                        <p class="text-[10px] sm:text-[11px] text-slate-500 mt-0.5">Jl. Ar-Rahmah No. 17, Ngasinan, Weleri, Kendal, Jawa Tengah &bull; Telp: (0294) 641870</p>
                    </div>
                </div>
                <div class="sm:text-right border-t sm:border-t-0 pt-2 sm:pt-0 border-slate-100">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Unit Kerja</span>
                    <span class="text-xs font-extrabold text-slate-700">Poliklinik Jantung & Penyakit Dalam</span>
                </div>
            </div>
        </div>

        <!-- Judul Dokumen -->
        <div class="text-center my-6">
            <h2 class="text-base font-extrabold tracking-wider uppercase text-slate-900 underline decoration-2 underline-offset-4">
                SURAT PENGANTAR PEMERIKSAAN SKRINING RISIKO HIPERTENSI
            </h2>
            <div class="text-xs text-slate-500 font-mono mt-1">
                Nomor: RSI-HT/{{ \Carbon\Carbon::parse($prediction->tanggal_prediksi)->format('Ymd') }}/{{ str_pad($prediction->id_prediction, 4, '0', STR_PAD_LEFT) }}
            </div>
        </div>

        <!-- Informasi Pasien -->
        <div class="space-y-4 text-xs">
            <div class="bg-slate-50 p-4 rounded-lg border border-slate-200">
                <h3 class="font-bold text-slate-800 uppercase tracking-wider mb-2.5 text-[11px] flex items-center">
                    <i class="fa-solid fa-user-tag text-sky-600 mr-1.5"></i> Identitas Pasien
                </h3>
                <div class="grid grid-cols-2 gap-y-2">
                    <div class="flex">
                        <span class="w-32 text-slate-500">Nama Pasien:</span>
                        <span class="font-bold text-slate-800">{{ $prediction->nama_pengguna }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-32 text-slate-500">ID Skrining:</span>
                        <span class="font-mono font-bold text-slate-800">#{{ $prediction->id_prediction }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-32 text-slate-500">Jenis Kelamin:</span>
                        <span class="font-semibold text-slate-800">{{ $prediction->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-32 text-slate-500">Usia:</span>
                        <span class="font-semibold text-slate-800">{{ $prediction->usia }} Tahun</span>
                    </div>
                    <div class="flex">
                        <span class="w-32 text-slate-500">Berat / Tinggi Badan:</span>
                        <span class="font-semibold text-slate-800">{{ $prediction->berat_badan }} kg / {{ $prediction->tinggi_badan }} cm</span>
                    </div>
                    <div class="flex">
                        <span class="w-32 text-slate-500">Indeks Massa Tubuh:</span>
                        <span class="font-bold text-slate-800">{{ $prediction->bmi }} kg/m&sup2;</span>
                    </div>
                </div>
            </div>

            <!-- Hasil Evaluasi Clinical Decision Support System -->
            <div class="border border-slate-200 rounded-lg p-4 space-y-3">
                <h3 class="font-bold text-slate-800 uppercase tracking-wider text-[11px] flex items-center">
                    <i class="fa-solid fa-stethoscope text-sky-600 mr-1.5"></i> Hasil Evaluasi Sistem Pendukung Keputusan (CDSS)
                </h3>
                
                <div class="p-3 rounded-lg border {{ $prediction->status_hipertensi == 'RISIKO TINGGI' ? 'bg-red-50 border-red-200 text-red-950' : 'bg-emerald-50 border-emerald-200 text-emerald-950' }}">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Status Klasifikasi Risiko</div>
                            <div class="text-base font-black mt-0.5">
                                {{ $prediction->status_hipertensi == 'RISIKO TINGGI' ? 'TERDETEKSI RISIKO TINGGI HIPERTENSI' : 'NORMOTENSI (RISIKO RENDAH)' }}
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Probabilitas Model</div>
                            <div class="text-base font-black">{{ $prediction->probabilitas }}%</div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2 text-[11px] pt-1">
                    <div>
                        <span class="text-slate-500">Model Komputasi:</span>
                        <span class="font-bold text-slate-700">{{ $prediction->modelVersion->versi_model ?? 'ExtraTrees Classifier v1.2' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-500">Ambang Batas Optimal:</span>
                        <span class="font-bold text-slate-700">81.5% (Akurasi: 85.21%)</span>
                    </div>
                </div>
            </div>

            <!-- Catatan & Rekomendasi Klinis Dokter -->
            <div class="border border-slate-200 rounded-lg p-4 space-y-2">
                <h3 class="font-bold text-slate-800 uppercase tracking-wider text-[11px] flex items-center">
                    <i class="fa-solid fa-user-doctor text-sky-600 mr-1.5"></i> Rekomendasi Tindak Lanjut Poliklinik
                </h3>
                @if($prediction->status_hipertensi == 'RISIKO TINGGI')
                    <p class="text-slate-700 leading-relaxed">
                        Pasien memiliki probabilitas prediksi melampaui ambang batas 81.5% yang mengindikasikan risiko tinggi kejadian hipertensi esensial. Disarankan untuk segera dilakukan konfirmasi tekanan darah klinis menggunakan sfigmomanometer standar oleh dokter jaga / dokter spesialis penyakit dalam, pemeriksaan laboratorium lanjutan (fungsi ginjal dan profil lipid), serta konseling gaya hidup rendah garam.
                    </p>
                @else
                    <p class="text-slate-700 leading-relaxed">
                        Hasil evaluasi menunjukkan parameter fisik berada dalam rentang normotensi (risiko rendah). Dianjurkan untuk mempertahankan indeks massa tubuh normal melalui diet seimbang, olahraga teratur minimal 150 menit per minggu, dan melakukan kontrol skrining ulang secara periodik.
                    </p>
                @endif
            </div>

            <!-- Lembar Pengesahan Medis -->
            <div class="pt-8 grid grid-cols-2 gap-4 items-end">
                <div>
                    <div class="w-24 h-24 border border-slate-300 rounded flex flex-col items-center justify-center p-2 text-center text-[10px] text-slate-400">
                        <i class="fa-solid fa-qrcode text-3xl text-slate-800 mb-1"></i>
                        <span>Validasi Digital</span>
                    </div>
                    <div class="text-[10px] text-slate-400 mt-1 font-mono">
                        UID: {{ md5($prediction->id_prediction . $prediction->tanggal_prediksi) }}
                    </div>
                </div>

                <div class="text-right space-y-1">
                    <div class="text-xs text-slate-600">
                        Kendal, {{ \Carbon\Carbon::parse($prediction->tanggal_prediksi)->translatedFormat('d F Y') }}
                    </div>
                    <div class="text-xs font-bold text-slate-800">
                        Dokter Penanggung Jawab Pelayanan (DPJP)
                    </div>
                    <div class="h-16 flex items-center justify-end">
                        <span class="text-xs font-serif italic text-slate-400">Tanda tangan digital terverifikasi</span>
                    </div>
                    <div class="font-bold text-slate-900 underline text-xs">
                        dr. Ahmad Fauzi, Sp.JP
                    </div>
                    <div class="text-[11px] text-slate-500">
                        SIP: 446/092/SIP-DS/DINKES/2024
                    </div>
                </div>
            </div>

        </div>

    </div>

</body>
</html>
