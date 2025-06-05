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
        Schema::create('general_stock_items', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->comment('Kode unik item');
            $table->string('name');
            $table->string('category'); // bibit, pakan, obat, dll
            $table->string('unit'); // kg, liter, pack, dll
            $table->decimal('minimum_stock', 10, 2)->default(0)->comment('Batas minimum stok');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes()->comment('Untuk arsip jika item dihapus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_stock_items');
    }
};
