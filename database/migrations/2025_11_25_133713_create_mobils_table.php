<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('mobils', function (Blueprint $table) {
        $table->id();
        $table->string('nama_mobil');
        $table->string('merk');
        $table->string('plat_nomor');
        $table->integer('tahun');
        $table->integer('harga_sewa');
        $table->enum('status', ['tersedia', 'disewa'])->default('tersedia');
        $table->string('gambar')->nullable();
        $table->text('deskripsi')->nullable();
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('mobils');
}
};