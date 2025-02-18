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
    Schema::table('presensi', function (Blueprint $table) {
        $table->enum('status', ['Masuk', 'Izin', 'Sakit', 'Alpha'])->default('Alpha');
    });
}

public function down()
{
    Schema::table('presensi', function (Blueprint $table) {
        $table->dropColumn('status');  // Hapus kolom status jika rollback
    });
}

};
