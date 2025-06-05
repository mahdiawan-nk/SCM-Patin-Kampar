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
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders');
            $table->enum('delivery_type', ['internal', 'eksternal']);
            $table->date('delivery_date');
            $table->dateTime('estimated_arrival');
            $table->dateTime('actual_arrival')->nullable();
            $table->enum('status', ['diproses', 'dikirim', 'tiba', 'gagal'])->default('diproses');
            $table->foreignId('driver_id')->nullable()->constrained('armada_drivers');
            $table->foreignId('vehicle_id')->nullable()->constrained('armada_vehicles');
            $table->string('courier_service')->nullable();
            $table->string('tracking_number')->nullable();
            $table->string('tracking_url')->nullable();
            $table->text('note')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['order_id', 'delivery_type', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
