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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id('id');
            $table->string('supplier_code')->unique()->comment('Kode unik supplier: SUPP-YYYYMM-XXX');
            $table->string('supplier_name', 100);
            $table->string('supplier_type')->default('umum')->comment('jenis supplier: pakan, obat, bibit, kemasan, umum');
            $table->string('contact_person', 100)->nullable();
            $table->string('phone', 20);
            $table->string('phone_alt', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->text('address')->nullable();
            $table->string('province', 50)->nullable();
            $table->string('city', 50)->nullable();
            $table->string('district', 50)->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });

        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('purchase_code')->unique(); // Format: PO-YYYYMMDD-XXX
            $table->unsignedBigInteger('supplier_id');
            $table->enum('purchase_type', ['pakan', 'obat', 'bibit', 'kemasan', 'lainnya']);
            $table->string('purchase_name')->nullable();
            $table->dateTime('purchase_date');
            $table->enum('delivery_status', ['draft', 'dipesan', 'diterima sebagian', 'lengkap', 'dibatalkan'])->default('draft');
            $table->decimal('total_amount', 15, 2);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('grand_total', 15, 2);
            $table->enum('payment_status', ['lunas', 'cicilan', 'belum bayar'])->default('belum bayar');
            $table->text('notes')->nullable();
            $table->uuid('created_by');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('supplier_id')->references('id')->on('suppliers');
            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('purchases');
    }
};
