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
        Schema::create('harvest_realizations', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->foreignId('kolam_budidaya_id')->constrained('kolam_budidayas')->onDelete('cascade');
            $table->foreignId('kolam_siklus_id')->constrained('kolam_siklus')->onDelete('cascade');
            $table->date('actual_harvest_at')->nullable()->comment('Aktual Tanggal panen');
            $table->integer('actual_harvest_amount')->nullable()->comment('Aktual Jumlah panen');
            $table->float('actual_harvest_weight')->nullable()->comment('Aktual Berat panen');
            $table->float('actual_harvest_percentage')->nullable()->comment('Aktual Persentase panen');
            $table->float('actual_survival_rate')->nullable();
            $table->text('note')->nullable()->comment('Catatan');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('harvest_realizations');
    }
};
