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
        Schema::table('kolam_feedings', function (Blueprint $table) {
            $table->dropColumn('feeding_stock_id');

            // Tambah kolom baru
            $table->foreignId('general_stock_item_id')
                ->after('kolam_siklus_id')
                ->constrained('general_stock_items')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kolam_feedings', function (Blueprint $table) {
            $table->dropForeign(['general_stock_item_id']);
            $table->dropColumn('general_stock_item_id');

            // Kembalikan kolom lama
            $table->foreignId('feeding_stock_id')
                ->after('kolam_siklus_id')
                ->constrained('stocks_feedings_drugs')
                ->onDelete('cascade');
        });
    }
};
