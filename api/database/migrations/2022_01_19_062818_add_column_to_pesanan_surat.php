<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToPesananSurat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pesanan_surat', function (Blueprint $table) {
            $table->string('penanggung_jawab');
            $table->enum('status', ['1', '0'])->comment('1 = disetujui, 0 = tidak');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pesanan_surat', function (Blueprint $table) {
            $table->dropColumn('penanggung_jawab');
            $table->dropColumn('status');
        });
    }
}
