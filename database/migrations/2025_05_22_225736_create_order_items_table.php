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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('product_type_id')->constrained('product_types')->onDelete('cascade');
            $table->foreignId('packaging_batch_id')->nullable()->constrained('packaging_batches')->onDelete('set null');
            $table->decimal('quantity', 10, 2);
            $table->string('unit')->nullable(); // kg, gram, pcs, etc.
            $table->decimal('price', 15, 2); // harga per unit saat order dibuat
            $table->softDeletes();
            $table->timestamps();

            $table->index(['order_id', 'product_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
