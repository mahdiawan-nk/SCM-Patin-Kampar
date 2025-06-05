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
        Schema::create('product_stock_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_stock_item_id')->constrained()->onDelete('cascade');
            $table->decimal('quantity', 10, 2)->default(0);
            $table->decimal('value', 12, 2)->default(0);
            $table->timestamp('last_updated')->nullable();
            $table->timestamps();

            $table->index(['product_stock_item_id', 'last_updated']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_stock_balances');
    }
};
