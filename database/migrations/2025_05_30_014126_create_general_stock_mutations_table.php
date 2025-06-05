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
        Schema::create('general_stock_mutations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('general_stock_item_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->enum('type', ['in', 'out', 'adjustment']);
            $table->decimal('quantity', 12, 3);
            $table->decimal('current_stock', 12, 3)->comment('Stok saat transaksi terjadi');
            $table->string('reference_type')->nullable()->comment('Model relasi jika ada');
            $table->unsignedBigInteger('reference_id')->nullable()->comment('ID relasi jika ada');
            $table->string('source')->nullable()->comment('Sumber untuk stok masuk');
            $table->string('usage_type')->nullable()->comment('Jenis penggunaan untuk stok keluar');
            $table->enum('adjustment_type', ['tambah', 'kurang'])->nullable();
            $table->string('reason')->nullable();
            $table->text('note')->nullable();
            $table->foreignId('created_by')->constrained('users')->comment('Operator yang membuat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_stock_mutations');
    }
};
