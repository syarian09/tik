<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUlangansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ulangans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('materi_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_akhir')->nullable();
            $table->longText('uraian')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ulangans');
    }
}
