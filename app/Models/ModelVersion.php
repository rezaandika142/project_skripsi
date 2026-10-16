<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelVersion extends Model
{
    use HasFactory;

    protected $table = 'model_versions';
    protected $primaryKey = 'id_model';

    protected $fillable = [
        'versi_model',
        'akurasi',
        'threshold_opt',
        'file_path',
        'tanggal_rilis',
    ];

    public function predictions()
    {
        return $this->hasMany(Prediction::class, 'id_model', 'id_model');
    }
}
