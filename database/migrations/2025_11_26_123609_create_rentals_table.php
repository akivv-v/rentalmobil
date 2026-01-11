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
    Schema::create('rentals', function (Blueprint $table) {
        $table->id();
        $table->foreignId('penyewa_id')->constrained('penyewas')->onDelete('cascade');
        $table->foreignId('mobil_id')->constrained('mobils')->onDelete('cascade');

        $table->string('penanggungjawab')->nullable(); // karyawan yang handle

        $table->date('tgl_sewa');
        $table->date('tgl_kembali');
        $table->integer('lama_sewa');

        $table->decimal('total_harga', 12, 2);

        // Pembayaran
        $table->decimal('dp', 12, 2)->nullable();
        $table->decimal('sisa_bayar', 12, 2)->nullable();

        // Denda hanya muncul jika telat mengembalikan mobil
        $table->decimal('denda', 12, 2)->nullable()->default(0);

        $table->enum('status', ['booking', 'disewa', 'selesai'])->default('booking');

        $table->timestamps();
    });
}
};
