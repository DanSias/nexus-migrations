<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBudgetScenariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budget_scenarios', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('year');
            $table->string('scenario');
            $table->string('status');
            $table->string('metric');
            $table->string('program');
            $table->string('channel');
            $table->string('initiative');
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
            $table->string('user');
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
        Schema::dropIfExists('budget_scenarios');
    }
}
