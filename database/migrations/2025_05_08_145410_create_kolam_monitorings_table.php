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
        Schema::create('kolam_monitorings', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('kolam_budidaya_id')->constrained('kolam_budidayas')->onDelete('cascade');
            $table->timestamp('tgl_monitoring')->nullable()->comment('Tanggal & waktu pengukuran');
            $table->float('temperature')->nullable()->comment('Suhu air (°C)');
            $table->float('ph')->nullable()->comment('ingkat keasaman air');
            $table->float('do')->nullable()->comment('Oksigen terlarut (mg/L)');
            $table->float('tds')->nullable()->comment('Total padatan terlarut (ppm)');
            $table->float('turbidity')->nullable()->comment('Kemurnian air (NTU)');
            $table->float('humidity')->nullable()->comment('Kelembapan (RH)');
            $table->float('brightness')->nullable()->comment('Kecerahan air (cm/Secchi disk)');
            $table->float('amonia')->nullable()->comment('Kandungan amonia (mg/L)');
            $table->float('nitrite')->nullable()->comment('Kandungan nitrit (mg/L)');
            $table->float('nitrate')->nullable()->comment('Kandungan nitrat (mg/L)');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kolam_monitorings');
    }
};
