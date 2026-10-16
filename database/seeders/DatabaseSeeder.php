<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ModelVersion;
use App\Models\Prediction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Tenaga Medis / Dokter Spesialis RSI Kendal
        $user = User::firstOrCreate(
            ['email' => 'dokter@rsikendal.com'],
            [
                'name' => 'dr. Ahmad Fauzi, Sp.JP',
                'password' => Hash::make('password123'),
                'role' => 'Dokter Spesialis'
            ]
        );

        // 2. Metadata Model ONNX Aktif
        $model = ModelVersion::firstOrCreate(
            ['versi_model' => 'ExtraTrees v1.2 (Optimized)'],
            [
                'akurasi' => 85.21,
                'threshold_opt' => 0.815,
                'file_path' => 'models/model_extratrees.onnx',
                'tanggal_rilis' => '2026-10-15',
            ]
        );

        // 3. Data Transaksi Rekam Medis Riil (Sesuai Gambar 4.9 Skripsi)
        $initialData = [
            [
                'nama_pengguna' => 'Tn. Supriyanto (092411)',
                'usia' => 52,
                'jenis_kelamin' => 'L',
                'berat_badan' => 74.00,
                'tinggi_badan' => 165.00,
                'bmi' => 27.18,
                'gds' => 145.0,
                'status_hipertensi' => 'RISIKO TINGGI',
                'probabilitas' => 90.91,
                'tanggal_prediksi' => now()->subHours(5)
            ],
            [
                'nama_pengguna' => 'Ny. Aminah (092412)',
                'usia' => 34,
                'jenis_kelamin' => 'P',
                'berat_badan' => 53.00,
                'tinggi_badan' => 157.00,
                'bmi' => 21.45,
                'gds' => 110.0,
                'status_hipertensi' => 'NORMOTENSI',
                'probabilitas' => 12.30,
                'tanggal_prediksi' => now()->subHours(4)
            ],
            [
                'nama_pengguna' => 'Tn. Hadi Prabowo (092413)',
                'usia' => 61,
                'jenis_kelamin' => 'L',
                'berat_badan' => 80.00,
                'tinggi_badan' => 165.00,
                'bmi' => 29.50,
                'gds' => 160.0,
                'status_hipertensi' => 'RISIKO TINGGI',
                'probabilitas' => 92.15,
                'tanggal_prediksi' => now()->subHours(3)
            ],
            [
                'nama_pengguna' => 'Sdr. Dimas (092414)',
                'usia' => 24,
                'jenis_kelamin' => 'L',
                'berat_badan' => 65.00,
                'tinggi_badan' => 169.00,
                'bmi' => 22.80,
                'gds' => 95.0,
                'status_hipertensi' => 'NORMOTENSI',
                'probabilitas' => 15.40,
                'tanggal_prediksi' => now()->subHours(2)
            ],
            [
                'nama_pengguna' => 'Ny. Sri Wahyuni (092415)',
                'usia' => 58,
                'jenis_kelamin' => 'P',
                'berat_badan' => 68.00,
                'tinggi_badan' => 156.00,
                'bmi' => 28.10,
                'gds' => 135.0,
                'status_hipertensi' => 'RISIKO TINGGI',
                'probabilitas' => 88.70,
                'tanggal_prediksi' => now()->subHour()
            ]
        ];

        foreach ($initialData as $row) {
            Prediction::create(array_merge($row, [
                'id_user' => $user->id,
                'id_model' => $model->id_model
            ]));
        }
    }
}
