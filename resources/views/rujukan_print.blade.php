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
        body {
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; }
            .print-border { border: 1px solid #e7e5e4 !important; box-shadow: none !important; }
        }
    </style>
</head>
<body class="bg-stone-100 min-h-screen py-4 sm:py-8 text-stone-800 antialiased px-2 sm:px-4">

    <!-- Action Toolbar (No Print) -->
    <div class="max-w-3xl mx-auto mb-4 flex flex-col sm:flex-row items-center justify-between gap-3 no-print">
        <a href="{{ route('skrining.index') }}"
           class="min-h-[40px] text-xs font-semibold text-stone-600 hover:text-stone-900 flex items-center gap-1.5 px-3 py-2 rounded-lg bg-white border border-stone-200 shadow-sm transition">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Skrining Mandiri</span>
        </a>
        <div class="flex items-center gap-2">
            <button onclick="window.print()"
                    class="min-h-[42px] bg-stone-900 hover:bg-stone-800 text-white font-semibold px-4 py-2 rounded-lg text-xs shadow-sm transition flex items-center gap-2 active:scale-95 cursor-pointer">
                <i class="fa-solid fa-file-pdf text-red-400"></i>
                <span>Cetak / Simpan PDF</span>
            </button>
        </div>
    </div>

    <!-- Petunjuk Unduh File (No Print) -->
    <div class="max-w-3xl mx-auto mb-4 bg-sky-50 border border-sky-200 text-sky-900 rounded-lg p-3 text-xs flex items-center gap-2.5 no-print shadow-sm">
        <i class="fa-solid fa-circle-info text-sky-600 text-base flex-shrink-0"></i>
        <span><strong>Cara Unduh PDF:</strong> Klik tombol <strong>Cetak / Simpan PDF</strong> di atas, lalu pilih Tujuan (Destination) sebagai <strong>"Simpan sebagai PDF" (Save as PDF)</strong> pada dialog peramban Anda.</span>
    </div>

    <!-- Printable Paper Sheet (A4 size styled) -->
    <div class="max-w-3xl mx-auto bg-white p-5 sm:p-8 md:p-12 rounded-xl shadow-sm border border-stone-200 print-border">

        <!-- Hospital Header / Kop Surat -->
        <div class="border-b-2 border-stone-900 pb-4 mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-xl bg-stone-900 text-white flex items-center justify-center text-2xl sm:text-3xl font-bold flex-shrink-0">
                        <i class="fa-solid fa-hospital"></i>
                    </div>
                    <div>
                        <h1 class="text-base sm:text-xl font-bold tracking-tight text-stone-900">RUMAH SAKIT ISLAM KENDAL</h1>
                        <p class="text-[11px] sm:text-xs text-stone-600">Pelayanan Kesehatan Terpadu, Islami, dan Berstandar Mutu</p>
                        <p class="text-[10px] sm:text-[11px] text-stone-500 mt-0.5">Jl. Ar-Rahmah No. 17, Ngasinan, Weleri, Kendal, Jawa Tengah &bull; Telp: (0294) 641870</p>
                    </div>
                </div>
                <div class="sm:text-right border-t sm:border-t-0 pt-2 sm:pt-0 border-stone-100">
                    <span class="text-[10px] font-semibold text-stone-400 block">Unit Kerja</span>
                    <span class="text-xs font-bold text-stone-700">Poliklinik Jantung & Penyakit Dalam</span>
                </div>
            </div>
        </div>

        <!-- Judul Dokumen -->
        <div class="text-center my-6">
            <h2 class="text-base font-bold tracking-wide uppercase text-stone-900 underline decoration-2 underline-offset-4">
                SURAT PENGANTAR PEMERIKSAAN SKRINING RISIKO HIPERTENSI
            </h2>
            <div class="text-xs text-stone-500 font-mono mt-1">
                Nomor: RSI-HT/{{ \Carbon\Carbon::parse($prediction->tanggal_prediksi)->format('Ymd') }}/{{ str_pad($prediction->id_prediction, 4, '0', STR_PAD_LEFT) }}
            </div>
        </div>

        <!-- Informasi Pasien -->
        <div class="space-y-4 text-xs">
            <div class="bg-stone-50 p-4 rounded-lg border border-stone-200">
                <h3 class="font-semibold text-stone-800 mb-2.5 text-xs flex items-center gap-1.5">
                    <i class="fa-solid fa-user-tag text-sky-600"></i>
                    <span>Identitas Pasien</span>
                </h3>
                <div class="grid grid-cols-2 gap-y-2">
                    <div class="flex">
                        <span class="w-32 text-stone-500">Nama Pasien:</span>
                        <span class="font-semibold text-stone-900">{{ $prediction->nama_pengguna }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-32 text-stone-500">ID Skrining:</span>
                        <span class="font-mono font-semibold text-stone-900">#{{ $prediction->id_prediction }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-32 text-stone-500">Jenis Kelamin:</span>
                        <span class="font-medium text-stone-800">{{ $prediction->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-32 text-stone-500">Usia:</span>
                        <span class="font-medium text-stone-800">{{ $prediction->usia }} Tahun</span>
                    </div>
                    <div class="flex">
                        <span class="w-32 text-stone-500">Berat / Tinggi:</span>
                        <span class="font-medium text-stone-800">{{ $prediction->berat_badan }} kg / {{ $prediction->tinggi_badan }} cm</span>
                    </div>
                    <div class="flex">
                        <span class="w-32 text-stone-500">Indeks Massa Tubuh:</span>
                        <span class="font-semibold text-stone-900">{{ $prediction->bmi }} kg/m&sup2;</span>
                    </div>
                </div>
            </div>

            <!-- Hasil Evaluasi CDSS -->
            <div class="border border-stone-200 rounded-lg p-4 space-y-3">
                <h3 class="font-semibold text-stone-800 text-xs flex items-center gap-1.5">
                    <i class="fa-solid fa-stethoscope text-sky-600"></i>
                    <span>Hasil Evaluasi Sistem Pendukung Keputusan (CDSS)</span>
                </h3>

                <div class="p-3 rounded-lg border {{ $prediction->status_hipertensi == 'RISIKO TINGGI' ? 'bg-red-50 border-red-200 text-red-950' : 'bg-emerald-50 border-emerald-200 text-emerald-950' }}">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-[10px] font-medium text-stone-500">Status Klasifikasi Risiko</div>
                            <div class="text-sm font-bold mt-0.5">
                                {{ $prediction->status_hipertensi == 'RISIKO TINGGI' ? 'TERDETEKSI RISIKO TINGGI HIPERTENSI' : 'NORMOTENSI (RISIKO RENDAH)' }}
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-[10px] font-medium text-stone-500">Probabilitas Model</div>
                            <div class="text-sm font-bold">{{ $prediction->probabilitas }}%</div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2 text-[11px] pt-1">
                    <div>
                        <span class="text-stone-500">Model Komputasi:</span>
                        <span class="font-medium text-stone-700">{{ $prediction->modelVersion->versi_model ?? 'ExtraTrees Classifier v1.2' }}</span>
                    </div>
                    <div>
                        <span class="text-stone-500">Ambang Batas Optimal:</span>
                        <span class="font-medium text-stone-700">81.5% (Akurasi: 85.21%)</span>
                    </div>
                </div>
            </div>

            <!-- Rekomendasi Klinis Dokter -->
            <div class="border border-stone-200 rounded-lg p-4 space-y-2">
                <h3 class="font-semibold text-stone-800 text-xs flex items-center gap-1.5">
                    <i class="fa-solid fa-user-doctor text-sky-600"></i>
                    <span>Rekomendasi Tindak Lanjut Poliklinik</span>
                </h3>
                @if($prediction->status_hipertensi == 'RISIKO TINGGI')
                    <p class="text-stone-700 leading-relaxed">
                        Pasien memiliki probabilitas prediksi melampaui ambang batas 81.5% yang mengindikasikan risiko tinggi kejadian hipertensi esensial. Disarankan untuk segera dilakukan konfirmasi tekanan darah klinis menggunakan sfigmomanometer standar oleh dokter jaga / dokter spesialis penyakit dalam, pemeriksaan laboratorium lanjutan (fungsi ginjal dan profil lipid), serta konseling gaya hidup rendah garam.
                    </p>
                @else
                    <p class="text-stone-700 leading-relaxed">
                        Hasil evaluasi menunjukkan parameter fisik berada dalam rentang normotensi (risiko rendah). Dianjurkan untuk mempertahankan indeks massa tubuh normal melalui diet seimbang, olahraga teratur minimal 150 menit per minggu, dan melakukan kontrol skrining ulang secara periodik.
                    </p>
                @endif
            </div>

            <!-- Lembar Pengesahan Medis -->
            <div class="pt-8 grid grid-cols-2 gap-4 items-end">
                <div>
                    <div class="w-24 h-24 border border-stone-300 rounded flex flex-col items-center justify-center p-2 text-center text-[10px] text-stone-400">
                        <i class="fa-solid fa-qrcode text-3xl text-stone-800 mb-1"></i>
                        <span>Validasi Digital</span>
                    </div>
                    <div class="text-[10px] text-stone-400 mt-1 font-mono">
                        UID: {{ md5($prediction->id_prediction . $prediction->tanggal_prediksi) }}
                    </div>
                </div>

                <div class="text-right space-y-1">
                    <div class="text-xs text-stone-600">
                        Kendal, {{ \Carbon\Carbon::parse($prediction->tanggal_prediksi)->translatedFormat('d F Y') }}
                    </div>
                    <div class="text-xs font-semibold text-stone-800">
                        Dokter Penanggung Jawab Pelayanan (DPJP)
                    </div>
                    <div class="h-16 flex items-center justify-end">
                        <span class="text-xs font-serif italic text-stone-400">Tanda tangan digital terverifikasi</span>
                    </div>
                    <div class="font-bold text-stone-900 underline text-xs">
                        dr. Ahmad Fauzi, Sp.JP
                    </div>
                    <div class="text-[11px] text-stone-500">
                        SIP: 446/092/SIP-DS/DINKES/2024
                    </div>
                </div>
            </div>

        </div>

    </div>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('autoPrint') === '1') {
                setTimeout(() => {
                    window.print();
                }, 500);
            }
        });
    </script>
</body>
</html>
