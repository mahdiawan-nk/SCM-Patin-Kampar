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
        Schema::create('schedule_budidayas', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->foreignId('kolam_budidaya_id')->constrained('kolam_budidayas')->onDelete('cascade');
            $table->enum('activity_type', ['seed', 'feed', 'treatment','harvest'])->default('seed');
            $table->string('title')->nullable()->comment('judul kegiatan');
            $table->date('schedule_at')->nullable()->comment('schedule kegiatan');
            $table->time('reminder_at')->nullable()->comment('reminder kegiatan');
            $table->boolean('is_done')->default(false);
            $table->text('note')->nullable()->comment('Catatan');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_budidayas');
    }
};
