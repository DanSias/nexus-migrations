<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProgramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('partner_id');
            $table->string('code');
            $table->string('active');
            $table->text('name');
            $table->string('partner');
            $table->string('strategy');
            $table->unsignedInteger('bu');
            $table->string('location');
            $table->string('vertical');
            $table->string('subvertical');
            $table->string('type');
            $table->string('level');
            $table->string('priority');
            $table->float('ltv');
            $table->string('concentrations');
            $table->string('tracks');
            $table->string('accreditation');
            $table->string('online_percent');
            $table->string('start_year');
            $table->string('start_month');
            $table->unsignedInteger('entry_points');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('programs');
    }
}
