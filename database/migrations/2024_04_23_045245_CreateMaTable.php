<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ma', function (Blueprint $table) {
            $table->id();
            $table->string('ncode')->unique();
            $table->string('title');
            $table->string('writer');
            $table->string('general_lastup');
            $table->string('last_get_date');
            $table->integer('general_all_no');
            $table->integer('records');
            $table->integer('sum_po');
            $table->integer('sum_un');
            $table->float('mean_mk');
            $table->float('mean_uf');
            $table->float('mean_c');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ma');
    }
};
