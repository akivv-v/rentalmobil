<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_id')->constrained()->onDelete('cascade');
            $table->integer('total_harga');     // dari rental
            $table->integer('dp')->default(0);  // jumlah DP yang dibayar
            $table->integer('sisa_bayar');      // total_harga - dp
            $table->string('metode')->nullable(); // cash, transfer, dll
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
