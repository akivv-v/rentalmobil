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
        Schema::create('pengembalians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_id')->constrained()->onDelete('cascade');
            $table->date('tgl_kembali');
            $table->string('kondisi_mobil');
            $table->integer('denda')->default(0);
            $table->integer('total_bayar');
            $table->timestamps();
        });
    }
};
