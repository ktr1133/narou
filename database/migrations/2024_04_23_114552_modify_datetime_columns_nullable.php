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
        Schema::table('point', function (Blueprint $table) {
            $table->datetime('sum_all')->nullable()->change();
            $table->datetime('sum_yearly')->nullable()->change();
            $table->datetime('sum_half')->nullable()->change();
            $table->datetime('sum_monthly')->nullable()->change();
            $table->datetime('2023-01-26 00:00:00')->nullable()->change();
            $table->datetime('2023-01-30 00:00:00')->nullable()->change();
            $table->datetime('2023-02-01 00:00:00')->nullable()->change();
            $table->datetime('2023-02-06 00:00:00')->nullable()->change();
            $table->datetime('2023-02-13 00:00:00')->nullable()->change();
            $table->datetime('2023-02-20 00:00:00')->nullable()->change();
            $table->datetime('2023-02-27 00:00:00')->nullable()->change();
            $table->datetime('2023-03-12 00:00:00')->nullable()->change();
            $table->datetime('2023-03-13 00:00:00')->nullable()->change();
            $table->datetime('2023-03-21 00:00:00')->nullable()->change();
            $table->datetime('2023-03-27 00:00:00')->nullable()->change();
            $table->datetime('2023-04-03 00:00:00')->nullable()->change();
            $table->datetime('2023-04-10 00:00:00')->nullable()->change();
            $table->datetime('2023-04-17 00:00:00')->nullable()->change();
            $table->datetime('2023-04-24 00:00:00')->nullable()->change();
            $table->datetime('2023-05-01 00:00:00')->nullable()->change();
            $table->datetime('2023-05-08 00:00:00')->nullable()->change();
            $table->datetime('2023-05-15 00:00:00')->nullable()->change();
            $table->datetime('2023-05-22 00:00:00')->nullable()->change();
            $table->datetime('2023-05-29 00:00:00')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
