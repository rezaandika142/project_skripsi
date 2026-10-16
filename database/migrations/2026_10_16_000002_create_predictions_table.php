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
        Schema::create('predictions', function (Blueprint $table) {
            $table->id('id_prediction');
            $table->unsignedBigInteger('id_user')->nullable()->index();
            $table->unsignedBigInteger('id_model')->nullable()->index();
            $table->string('nama_pengguna')->default('Pasien Mandiri');
            $table->integer('usia');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->decimal('berat_badan', 5, 2);
            $table->decimal('tinggi_badan', 5, 2);
            $table->decimal('bmi', 5, 2);
            $table->decimal('gds', 5, 2)->nullable();
            $table->string('status_hipertensi'); // RISIKO TINGGI / NORMOTENSI
            $table->decimal('probabilitas', 5, 2); // Nilai persen, misal 90.91
            $table->timestamp('tanggal_prediksi')->useCurrent();
            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('users')->nullOnDelete();
            $table->foreign('id_model')->references('id_model')->on('model_versions')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('predictions');
    }
};
