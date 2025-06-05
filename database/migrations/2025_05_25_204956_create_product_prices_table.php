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
        Schema::create('product_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_type_id')->constrained('product_types')->onDelete('cascade');
            $table->decimal('cost_price', 10, 2)->nullable();
            $table->decimal('margin', 5, 2)->nullable();
            $table->decimal('selling_price', 10, 2);
            $table->date('effective_date')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['product_type_id', 'effective_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_prices');
    }
};
