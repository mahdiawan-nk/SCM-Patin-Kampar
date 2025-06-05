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
        Schema::create('stocks_feedings_drugs', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->enum('type', ['pakan', 'obat'])->default('pakan');
            $table->string('name')->nullable()->comment('Jenis pakan/obat');
            $table->float('jumlah')->nullable()->comment('Jumlah pakan/obat (kg)');
            $table->integer('satuan')->nullable()->comment('Satuan pakan/obat');
            $table->timestamp('kadaluarsa_at')->nullable()->comment('Tanggal kadaluarsa pakan/obat');
            $table->text('note')->nullable()->comment('Catatan pakan/obat');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_feeding_drugs');
    }
};
