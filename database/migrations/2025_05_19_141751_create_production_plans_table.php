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
        Schema::create('production_plans', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->date('plan_date');
            $table->foreignId('product_type_id')->constrained('product_types')->onDelete('cascade');
            $table->decimal('planned_quantity', 10, 2);
            $table->enum('status', ['rencana', 'diproses', 'selesai'])->default('rencana');
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
        Schema::dropIfExists('production_plans');
    }
};
