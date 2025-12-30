<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('jam_kerja_id')->constrained()->cascadeOnDelete();
            $table->date('tanggal');
            $table->time('jam_absen');
            $table->string('foto_selfie'); // path ke storage
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->enum('status', ['tepat_waktu', 'terlambat']);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Unique: 1 user hanya bisa absen 1x per hari
            $table->unique(['user_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};