<?php

namespace App\Http\Controllers;

use App\Models\Prediction;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $query = Prediction::with(['user', 'modelVersion'])->orderBy('tanggal_prediksi', 'desc');

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where('nama_pengguna', 'like', "%{$search}%");
        }

        if ($request->filled('status') && in_array($request->status, ['RISIKO TINGGI', 'NORMOTENSI'])) {
            $query->where('status_hipertensi', $request->status);
        }

        $predictions = $query->paginate(10)->withQueryString();
        $totalRecords = Prediction::count();

        return view('riwayat', compact('predictions', 'totalRecords'));
    }

    public function exportCsv()
    {
        $records = Prediction::orderBy('tanggal_prediksi', 'desc')->get();
        $filename = "audit_riwayat_skrining_hipertensi_" . date('Ymd_His') . ".csv";

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID', 'Tanggal / Jam', 'Nama Pasien', 'Usia', 'Gender', 'Berat Badan (kg)', 'Tinggi Badan (cm)', 'BMI', 'Status Risiko', 'Probabilitas (%)'];

        $callback = function() use($records, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($records as $row) {
                fputcsv($file, [
                    $row->id_prediction,
                    $row->tanggal_prediksi,
                    $row->nama_pengguna,
                    $row->usia,
                    $row->jenis_kelamin,
                    $row->berat_badan,
                    $row->tinggi_badan,
                    $row->bmi,
                    $row->status_hipertensi,
                    $row->probabilitas . '%'
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function destroy($id)
    {
        $prediction = Prediction::findOrFail($id);
        $prediction->delete();

        return redirect()->route('riwayat.index')->with('success', 'Catatan riwayat skrining berhasil dihapus.');
    }
}
