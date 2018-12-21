<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLaggingIndicatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lagging_indicators', function (Blueprint $table) {
            $table->string('measurement');
            $table->string('gradugrdflag');
            $table->string('programname');
            $table->unsignedInteger('bu');
            $table->string('program');
            $table->unsignedInteger('yearmonth');
            $table->string('college');
            $table->string('advisor');
            $table->string('admmanager');
            $table->string('secondmanager');
            $table->unsignedInteger('avgdaysinsalestoapp');
            $table->unsignedInteger('avgdaysapptocf');
            $table->unsignedInteger('avgdayscftoacc');
            $table->unsignedInteger('avgdaysacctoaccconf');
            $table->unsignedInteger('insales');
            $table->unsignedInteger('app');
            $table->unsignedInteger('cf');
            $table->unsignedInteger('acc');
            $table->unsignedInteger('accconf');
            $table->unsignedInteger('inquiries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lagging_indicators');
    }
}
