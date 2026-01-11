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
    Schema::create('penyewas', function (Blueprint $table) {
        $table->id();
        $table->string('nama');
        $table->string('email')->unique();
        $table->string('no_telp');
        $table->string('pekerjaan')->nullable();
        $table->text('alamat')->nullable();
        $table->string('foto_ktp')->nullable();
        $table->timestamps();
    });
}
};
