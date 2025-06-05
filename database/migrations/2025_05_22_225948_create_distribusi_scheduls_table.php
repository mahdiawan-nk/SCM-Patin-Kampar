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
        Schema::create('distribusi_scheduls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_id')->constrained('deliveries');
            $table->date('scheduled_date');
            $table->string('time_window'); // Format: "HH:MM-HH:MM"
            $table->enum('status', ['dijadwalkan', 'berlangsung', 'selesai'])->default('dijadwalkan');
            $table->text('note')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['delivery_id', 'scheduled_date', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distribusi_scheduls');
    }
};
