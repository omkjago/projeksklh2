<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePresensiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('presensi', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('tanggal_presensi');
            $table->time('jam_in');
            $table->string('foto_in');
            $table->string('lokasi_in');
            $table->time('jam_out')->nullable();
            $table->string('foto_out')->nullable();
            $table->string('lokasi_out')->nullable();
            $table->timestamps();
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('presensi', function (Blueprint $table) {
            $table->dropForeign(['siswa_id']);
            $table->dropForeign(['kelas_id']);
            $table->dropColumn(['siswa_id', 'kelas_id']);
        });
    }
}
