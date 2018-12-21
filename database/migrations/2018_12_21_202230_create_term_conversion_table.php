<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTermConversionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('term_conversion', function (Blueprint $table) {
            $table->string('location');
            $table->unsignedInteger('bu');
            $table->string('program');
            $table->string('vintage');
            $table->unsignedInteger('year');
            $table->string('term');
            $table->string('year_term');
            $table->unsignedInteger('leads');
            $table->unsignedInteger('plan_leads');
            $table->unsignedInteger('starts');
            $table->unsignedInteger('plan_starts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('term_conversion');
    }
}
