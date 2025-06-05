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
        Schema::create('kolam_growths', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('kolam_siklus_id')->constrained('kolam_siklus')->onDelete('cascade');
            $table->timestamp('grow_at')->nullable()->comment('Tanggal pertumbuhan');
            $table->float('avg_weight')->nullable()->comment('Berat rata-rata (gram)');
            $table->float('avg_length')->nullable()->comment('Panjang rata-rata (cm)');
            $table->integer('mortality')->nullable()->comment('Kematian (ekor)');
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
        Schema::dropIfExists('kolam_growths');
    }
};
