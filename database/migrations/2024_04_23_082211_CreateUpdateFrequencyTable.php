<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUpdateFrequencyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('update_frequency', function (Blueprint $table) {
            $table->id();
            $table->string('ncode')->unique();
            $table->integer('mean');
            $table->integer('mean_yearly');
            $table->integer('mean_half');
            $table->integer('mean_monthly');
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
            $table->dateTime('2023-06-06 00:00:00');
            $table->dateTime('2023-06-12 12:34:28');
            $table->dateTime('2023-06-19 13:21:28');
            $table->dateTime('2023-06-26 09:10:36');
            $table->dateTime('2023-07-03 09:53:49');
            $table->dateTime('2023-07-10 09:23:24');
            $table->dateTime('2023-07-18 13:59:17');
            $table->dateTime('2023-07-24 09:31:33');
            $table->dateTime('2023-07-31 12:35:17');
            $table->dateTime('2023-08-07 09:28:40');
            $table->dateTime('2023-08-14 11:54:49');
            $table->dateTime('2023-08-21 19:21:43');
            $table->dateTime('2023-08-28 09:05:03');
            $table->dateTime('2023-09-04 09:26:56');
            $table->dateTime('2023-09-11 08:29:46');
            $table->dateTime('2023-09-18 11:26:20');
            $table->dateTime('2023-09-25 10:42:18');
            $table->dateTime('2023-10-02 21:39:07');
            $table->dateTime('2023-10-09 18:21:37');
            $table->dateTime('2023-10-16 20:51:16');
            $table->dateTime('2023-10-23 20:18:31');
            $table->dateTime('2023-11-05 10:15:15');
            $table->dateTime('2023-11-06 22:54:58');
            $table->dateTime('2023-11-13 23:51:13');
            $table->dateTime('2023-11-20 22:44:10');
            $table->dateTime('2023-11-27 22:16:21');
            $table->dateTime('2023-12-04 16:08:47');
            $table->dateTime('2023-12-11 22:33:32');
            $table->dateTime('2023-12-18 21:47:17');
            $table->dateTime('2023-12-25 16:03:29');
            $table->dateTime('2024-01-01 23:38:17');
            $table->dateTime('2024-01-08 17:58:25');
            $table->dateTime('2024-01-15 21:54:48');
            $table->dateTime('2024-01-23 22:14:16');
            $table->dateTime('2024-01-29 22:48:12');
            $table->dateTime('2024-02-05 22:59:33');
            $table->dateTime('2024-02-12 22:14:40');
            $table->dateTime('2024-02-19 22:41:30');
            $table->dateTime('2024-02-26 22:15:33');
            $table->dateTime('2024-03-04 23:04:50');
            $table->dateTime('2024-03-11 09:25:35');
            $table->dateTime('2024-03-18 22:40:24');
            $table->dateTime('2024-03-25 22:34:20');
            $table->dateTime('2024-04-02 22:13:12');
            $table->dateTime('2024-04-08 14:36:24');
            $table->dateTime('2024-04-15 22:58:13');
            $table->dateTime('2024-04-22 22:15:51');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('update_frequency');
    }
};