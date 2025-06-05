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
        Schema::create('pembudidayas', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('nama_lengkap');
            $table->string('nik', 16)->unique();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->date('tanggal_lahir')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('email')->nullable();
            $table->text('alamat_lengkap')->nullable();
            $table->timestamp('tgl_bergabung')->default(now());
            $table->enum('status', ['aktif', 'tidak aktif'])->default('aktif');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('pembudidayas_usaha', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('pembudidaya_id')->constrained('pembudidayas')->onDelete('cascade');
            $table->string('nama_usaha')->nullable();
            $table->string('jenis_usaha')->nullable()->default('pembudidaya');
            $table->integer('luas_lahan')->nullable();
            $table->integer('jumlah_kolam')->nullable();
            $table->string('sistem_budidaya')->nullable();
            $table->string('jenis_izin_usaha')->nullable();
            $table->year('tahun_mulai_usaha')->nullable();
            $table->enum('status_kepemilikan_usaha', ['milik sendiri', 'sewa', 'kelompok'])->nullable();
            $table->string('nama_kelompok')->nullable();
            $table->string('jabatan_di_kelompok')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kabupaten')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('desa')->nullable();
            $table->text('alamat_usaha')->nullable();
            $table->json('kordinat')->nullable();
            $table->string('no_izin_usaha')->nullable();
            $table->string('ktp_scan')->nullable();
            $table->string('foto_lokasi')->nullable();
            $table->string('surat_izin')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembudidayas');
        Schema::dropIfExists('pembudidayas_usaha');
    }
};
