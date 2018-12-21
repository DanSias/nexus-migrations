<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRevenueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('revenue', function (Blueprint $table) {
            $table->string('location');
            $table->string('top200');
            $table->string('partner');
            $table->string('program_rolled');
            $table->unsignedInteger('bu');
            $table->string('program');
            $table->unsignedInteger('year');
            $table->string('term');
            $table->string('vintage');
            $table->string('discipline');
            $table->float('starts');
            $table->float('students');
            $table->float('revenue');
            $table->float('plan_starts');
            $table->float('plan_students');
            $table->float('plan_revenue');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('revenue');
    }
}
