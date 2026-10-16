<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prediction extends Model
{
    use HasFactory;

    protected $table = 'predictions';
    protected $primaryKey = 'id_prediction';

    protected $fillable = [
        'id_user',
        'id_model',
        'nama_pengguna',
        'usia',
        'jenis_kelamin',
        'berat_badan',
        'tinggi_badan',
        'bmi',
        'gds',
        'status_hipertensi',
        'probabilitas',
        'tanggal_prediksi',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function modelVersion()
    {
        return $this->belongsTo(ModelVersion::class, 'id_model', 'id_model');
    }
}
