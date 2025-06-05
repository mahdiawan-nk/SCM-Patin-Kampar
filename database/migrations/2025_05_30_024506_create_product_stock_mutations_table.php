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
        Schema::create('product_stock_mutations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_stock_item_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->enum('type', ['in', 'out', 'adjustment']);
            $table->decimal('quantity', 10, 2);
            $table->decimal('current_stock', 10, 2)->default(0);

            $table->string('reference_type')->nullable(); // morph: e.g., HarvestRealization
            $table->unsignedBigInteger('reference_id')->nullable();

            $table->string('source')->nullable(); // panen, produksi, distribusi
            $table->string('note')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['product_stock_item_id', 'created_by']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_stock_mutations');
    }
};
