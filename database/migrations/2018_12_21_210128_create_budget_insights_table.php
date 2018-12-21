<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBudgetInsightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budget_insights', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('year');
            $table->string('scenario');
            $table->string('program');
            $table->string('channel');
            $table->string('initiative');
            $table->string('metric');
            $table->text('notes');
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
        Schema::dropIfExists('budget_insights');
    }
}
