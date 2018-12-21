<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMonthlyMetricsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monthly_metrics', function (Blueprint $table) {
            $table->string('metric');
            $table->string('location');
            $table->unsignedInteger('bu');
            $table->string('partner');
            $table->string('program');
            $table->string('channel');
            $table->string('initiative');
            $table->unsignedInteger('year');
            $table->float('january');
            $table->float('february');
            $table->float('march');
            $table->float('april');
            $table->float('may');
            $table->float('june');
            $table->float('july');
            $table->float('august');
            $table->float('september');
            $table->float('october');
            $table->float('november');
            $table->float('december');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('monthly_metrics');
    }
}
