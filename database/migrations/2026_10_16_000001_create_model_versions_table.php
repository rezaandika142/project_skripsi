<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('model_versions', function (Blueprint $table) {
            $table->id('id_model');
            $table->string('versi_model');
            $table->decimal('akurasi', 5, 2)->default(85.21);
            $table->decimal('threshold_opt', 4, 3)->default(0.815);
            $table->string('file_path')->default('models/model_extratrees.onnx');
            $table->date('tanggal_rilis')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('model_versions');
    }
};
