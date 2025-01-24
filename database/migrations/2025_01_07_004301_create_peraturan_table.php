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
        Schema::create('bpm_msdokumen', function (Blueprint $table) {
            $table->id('dok_id');
            $table->string('kdo_id', 11)->nullable();
            $table->string('men_id', 11)->nullable();
            $table->string('dok_judul', 100)->nullable();
            $table->string('dok_nomor_induk', 50)->nullable();
            $table->date('dok_tgl_berlaku')->nullable();
            $table->date('dok_tgl_kadaluarsa')->nullable();
            $table->string('dok_file', 255)->nullable();
            $table->string('dok_control', 30)->nullable();
            $table->string('dok_status_file', 50)->nullable();
            $table->string('dok_referensi', 11)->nullable();
            $table->string('dok_revisi', 11)->nullable();
            $table->string('dok_bagian_prosedur', 50)->nullable();
            $table->dateTime('dok_tgl_unduh')->nullable();
            $table->string('dok_status', 15)->nullable();
            $table->string('dok_created_by', 50)->nullable();
            $table->dateTime('dok_created_date')->nullable();
            $table->string('dok_modif_by', 50)->nullable();
            $table->dateTime('dok_modif_date')->nullable();
            $table->timestamps();
     
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bpm_msdokumen');
    }
};



