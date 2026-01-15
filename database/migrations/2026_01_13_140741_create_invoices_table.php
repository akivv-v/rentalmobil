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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_id')->constrained()->cascadeOnDelete();
            $table->string('kode_invoice')->unique();
            $table->integer('total_tagihan');
            $table->string('metode_pembayaran');
            $table->integer('jumlah_dibayar')->nullable();
            $table->string('bukti_bayar')->nullable();
            $table->enum('status', ['pending', 'menunggu_verifikasi', 'lunas']);
            $table->timestamp('tanggal_bayar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
