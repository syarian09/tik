<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJawabansTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('jawabans', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
      $table->foreignId('ulangan_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
      $table->integer('token')->unsigned()->nullable();
      $table->longText('jawaban')->nullable();
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
    Schema::dropIfExists('jawabans');
  }
}
