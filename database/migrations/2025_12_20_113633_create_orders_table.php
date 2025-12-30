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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('paket_id')->constrained()->cascadeOnDelete();

            $table->integer('jumlah');

            $table->enum('pickup', ['antar_sendiri', 'dijemput']);
            $table->decimal('jarak_km', 5, 2)->nullable();
            $table->integer('biaya_pickup')->default(0);

            $table->integer('total_harga');

            $table->integer('antrian');

            $table->enum('status', [
                'menunggu',
                'diproses',
                'selesai',
                'diambil',
            ])->default('menunggu');

            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
