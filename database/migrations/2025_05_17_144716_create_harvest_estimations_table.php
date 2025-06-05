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
        Schema::create('harvest_estimations', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->foreignId('kolam_budidaya_id')->constrained('kolam_budidayas')->onDelete('cascade');
            $table->foreignId('kolam_siklus_id')->constrained('kolam_siklus')->onDelete('cascade');
            $table->date('estimation_harvest_at')->nullable()->comment('Perkiraan Tanggal panen');
            $table->integer('estimation_harvest_amount')->nullable()->comment('Perkiraan Jumlah panen');
            $table->float('estimation_harvest_weight')->nullable()->comment('Perkiraan Berat panen');
            $table->float('estimation_harvest_percentage')->nullable()->comment('Perkiraan Persentase panen');
            $table->float('estimate_survival_rate')->nullable();
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
        Schema::dropIfExists('harvest_estimations');
    }
};
