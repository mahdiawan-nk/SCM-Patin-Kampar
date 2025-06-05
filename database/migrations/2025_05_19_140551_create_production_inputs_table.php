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
        Schema::create('production_inputs', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->date('production_date');
            $table->enum('source_type',['panen','stok','lainnya'])->default('panen');
            $table->string('source_name')->nullable();
            $table->decimal('total_weight_kg', 10, 2);
            $table->text('note')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_inputs');
    }
};
