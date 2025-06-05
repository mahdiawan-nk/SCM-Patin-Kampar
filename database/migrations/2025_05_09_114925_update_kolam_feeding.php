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
            $table->string('feeding_name')->nullable()->after('feeding_stock_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kolam_feedings', function (Blueprint $table) {
            $table->dropColumn('feeding_name');
        });
    }
};
