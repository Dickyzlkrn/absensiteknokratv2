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
        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->string('npm', 20)->change();
            $table->string('nama_mhs', 255)->change();
            $table->string('prodi', 255)->change();
            $table->string('nohp_mhs', 100)->nullable()->change();

            $table->text('foto')->nullable()->change();
            $table->string('password')->nullable()->change();
            $table->string('tempat_pkl')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->integer('npm')->change();
            $table->string('nama_mhs')->change();
            $table->string('prodi')->change();
            $table->string('nohp_mhs')->change();
            $table->text('foto')->nullable()->change();
            $table->string('password')->nullable()->change();
            $table->string('tempat_pkl')->nullable()->change();
        });
    }
};
