<?php

namespace App\Http\Controllers;

use App\Models\Prediction;
use App\Models\ModelVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SkriningController extends Controller
{
    public function index()
    {
        $activeModel = ModelVersion::orderBy('id_model', 'desc')->first();
        if (!$activeModel) {
            $activeModel = (object)[
                'id_model' => 1,
                'versi_model' => 'ExtraTrees v1.2 (Optimized)',
                'akurasi' => 85.21,
                'threshold_opt' => 0.815,
                'file_path' => 'models/model_extratrees.onnx',
            ];
        }

        return view('skrining', compact('activeModel'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pengguna'    => 'nullable|string|max:150',
            'usia'             => 'required|numeric|min:18|max:120',
            'jenis_kelamin'    => 'required|in:L,P',
            'berat_badan'      => 'required|numeric|min:25|max:250',
            'tinggi_badan'     => 'required|numeric|min:100|max:230',
            'bmi'              => 'required|numeric',
            'status_hipertensi'=> 'required|string',
            'probabilitas'     => 'required|numeric',
        ]);

        $activeModel = ModelVersion::orderBy('id_model', 'desc')->first();
        $modelId = $activeModel ? $activeModel->id_model : null;

        $prediction = Prediction::create([
            'id_user'          => Auth::id() ?? null,
            'id_model'         => $modelId,
            'nama_pengguna'    => $validated['nama_pengguna'] ?: 'Pasien Mandiri',
            'usia'             => $validated['usia'],
            'jenis_kelamin'    => $validated['jenis_kelamin'],
            'berat_badan'      => $validated['berat_badan'],
            'tinggi_badan'     => $validated['tinggi_badan'],
            'bmi'              => $validated['bmi'],
            'status_hipertensi'=> $validated['status_hipertensi'],
            'probabilitas'     => $validated['probabilitas'],
            'tanggal_prediksi' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Hasil skrining mandiri berhasil dicatat ke dalam basis data.',
            'id_prediction' => $prediction->id_prediction,
        ]);
    }

    public function printRujukan(Request $request, $id = null)
    {
        $prediction = null;
        if ($id && is_numeric($id)) {
            $prediction = Prediction::with('modelVersion')->find($id);
        }

        // Fallback jika id tidak ditemukan atau request berasal dari preview langsung tanpa DB
        if (!$prediction) {
            $prediction = (object)[
                'id_prediction'   => ($id && is_numeric($id)) ? (int)$id : rand(100, 999),
                'nama_pengguna'   => $request->query('nama') ?: 'Pasien Mandiri',
                'usia'            => (int)($request->query('usia') ?: 45),
                'jenis_kelamin'   => $request->query('jk') ?: 'L',
                'berat_badan'     => (float)($request->query('bb') ?: 65),
                'tinggi_badan'    => (float)($request->query('tb') ?: 165),
                'bmi'             => (float)($request->query('bmi') ?: 23.88),
                'status_hipertensi'=> $request->query('status') ?: 'RISIKO TINGGI',
                'probabilitas'    => (float)($request->query('prob') ?: 85.5),
                'tanggal_prediksi'=> now(),
                'modelVersion'    => (object)[
                    'versi_model' => 'ExtraTrees Classifier v1.2 (Optimized)'
                ]
            ];
        }

        return view('rujukan_print', compact('prediction'));
    }
}
