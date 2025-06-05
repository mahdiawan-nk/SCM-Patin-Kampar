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
        Schema::create('kolam_feedings', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('kolam_siklus_id')->constrained('kolam_siklus')->onDelete('cascade');
            $table->foreignId('feeding_stock_id')->constrained('stocks_feedings_drugs')->onDelete('cascade');
            $table->timestamp('feed_at')->nullable()->comment('Tanggal pemberian pakan');
            $table->float('feed_amount')->nullable()->comment('Jumlah pakan diberikan (kg)');
            $table->integer('frequency')->nullable()->comment('Frekuensi per hari (kali/hari)');
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
        Schema::dropIfExists('kolam_feedings');
    }
};
