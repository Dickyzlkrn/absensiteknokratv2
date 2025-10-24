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
        Schema::table('pengajuan_izin', function (Blueprint $table) {
            $table->text('deskripsi_izin')->nullable()->after('status');
            $table->char('status_approved', 1)->default('0')->after('deskripsi_izin'); // 0 = pending, 1 = approved, 2 = rejected
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_izin', function (Blueprint $table) {
            $table->dropColumn(['deskripsi_izin', 'status_approved']);
            $table->dropTimestamps();
        });
    }
};
