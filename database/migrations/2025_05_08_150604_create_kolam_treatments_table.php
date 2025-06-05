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
        Schema::create('kolam_treatments', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('kolam_siklus_id')->constrained('kolam_siklus')->onDelete('cascade');
            $table->timestamp('treat_at')->nullable()->comment('Tanggal pemberian obat');
            $table->string('disease')->nullable()->comment('Penyakit');
            $table->string('medication')->nullable()->comment('Obat');
            $table->string('dosage')->nullable()->comment('Dosis');
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
        Schema::dropIfExists('kolam_treatments');
    }
};
