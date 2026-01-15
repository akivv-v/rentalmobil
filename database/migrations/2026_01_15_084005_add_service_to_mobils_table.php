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
        Schema::table('mobils', function (Blueprint $table) {
            $table->date('tgl_servis_terakhir')->nullable(); // Kapan terakhir servis
            $table->integer('interval_servis')->default(3); // Dalam bulan (3 atau 6)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mobils', function (Blueprint $table) {
            //
        });
    }
};
