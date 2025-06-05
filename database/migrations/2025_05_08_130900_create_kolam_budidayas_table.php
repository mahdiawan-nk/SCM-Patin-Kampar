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
        Schema::create('kolam_budidayas', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('pembudidaya_id')->constrained('pembudidayas')->onDelete('cascade');
            $table->string('nama_kolam');
            $table->string('lokasi_kolam');
            $table->float('panjang');
            $table->float('lebar');
            $table->float('kedalaman');
            $table->float('volume_air');
            $table->integer('kapasitas');
            $table->enum('jenis_kolam', ['tanah', 'terpal', 'beton', 'keramba'])->default('tanah');
            $table->enum('status', ['aktif', 'maintenance', 'tidak aktif'])->default('aktif');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kolam_budidayas');
    }
};
