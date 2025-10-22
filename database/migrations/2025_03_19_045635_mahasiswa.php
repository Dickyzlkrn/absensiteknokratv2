<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->string('npm')->primary();
            $table->string('nama_mhs');
            $table->string('prodi');
            $table->string('nohp_mhs');
            $table->string('password')->nullable();
            $table->string('tempat_pkl')->nullable();
            $table->text('foto')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
