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
        Schema::create('kolam_siklus', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('kolam_budidaya_id')->constrained('kolam_budidayas')->onDelete('cascade');
            $table->string('strain')->nullable()->comment('Jenis Ikan');
            $table->timestamp('start_date')->nullable()->comment('Tanggal tebar benih');
            $table->integer('initial_stock')->nullable()->comment('Jumlah benih awal');
            $table->float('initial_avg_weight')->nullable()->comment('Berat rata-rata benih (gram)');
            $table->float('stocking_density')->nullable()->comment('Kepadatan tebar (ekor/m²)');
            $table->enum('status', ['berjalan', 'selesai'])->default('berjalan');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kolam_sikluses');
    }
};
