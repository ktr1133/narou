<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePointTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('point', function (Blueprint $table) {
            $table->id();
            $table->string('ncode')->unique();
            $table->integer('sum_all');
            $table->integer('sum_yearly');
            $table->integer('sum_half');
            $table->integer('sum_monthly');
            $table->dateTime('2023-01-26 00:00:00');
            $table->dateTime('2023-01-30 00:00:00');
            $table->dateTime('2023-02-01 00:00:00');
            $table->dateTime('2023-02-06 00:00:00');
            $table->dateTime('2023-02-13 00:00:00');
            $table->dateTime('2023-02-20 00:00:00');
            $table->dateTime('2023-02-27 00:00:00');
            $table->dateTime('2023-03-12 00:00:00');
            $table->dateTime('2023-03-13 00:00:00');
            $table->dateTime('2023-03-21 00:00:00');
            $table->dateTime('2023-03-27 00:00:00');
            $table->dateTime('2023-04-03 00:00:00');
            $table->dateTime('2023-04-10 00:00:00');
            $table->dateTime('2023-04-17 00:00:00');
            $table->dateTime('2023-04-24 00:00:00');
            $table->dateTime('2023-05-01 00:00:00');
            $table->dateTime('2023-05-08 00:00:00');
            $table->dateTime('2023-05-15 00:00:00');
            $table->dateTime('2023-05-22 00:00:00');
            $table->dateTime('2023-05-29 00:00:00');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('point');
    }
};
