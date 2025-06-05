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
        Schema::create('production_dailies', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->date('production_date');
            $table->foreignId('product_type_id')->constrained('product_types')->onDelete('cascade');
            $table->foreignId('production_input_id')->constrained('production_inputs')->onDelete('cascade');
            $table->decimal('production_quantity', 10, 2);
            $table->string('batch_code')->nullable();
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
        Schema::dropIfExists('production_dailies');
    }
};
