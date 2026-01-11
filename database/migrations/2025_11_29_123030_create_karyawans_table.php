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
    Schema::create('karyawans', function (Blueprint $table) {
        $table->id();
        $table->string('nama');
        $table->string('email')->unique();
        $table->string('no_telp');
        $table->string('alamat')->nullable();
        $table->string('jabatan')->nullable(); // pengganti NIK (opsional)
        $table->timestamps();
    });
}

};
