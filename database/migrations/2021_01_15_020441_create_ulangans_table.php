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
      $table->date('tanggal_mulai')->nullable();
      $table->date('tanggal_akhir')->nullable();
      $table->integer('waktu')->unsigned()->nullable();
      $table->string('judul')->nullable();
      $table->text('soal')->nullable();
      $table->string('kelas_id')->nullable();
      $table->integer('aktif')->unsigned()->nullable()->default(0);
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
