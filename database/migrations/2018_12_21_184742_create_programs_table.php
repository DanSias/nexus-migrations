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
            $table->unsignedInteger('partner_id')->nullable();
            $table->string('code');
            $table->string('active')->nullable();
            $table->text('name')->nullable();
            $table->string('partner')->nullable();
            $table->string('strategy')->nullable();
            $table->unsignedInteger('bu')->nullable();
            $table->string('location')->nullable();
            $table->string('vertical')->nullable();
            $table->string('subvertical')->nullable();
            $table->string('type')->nullable();
            $table->string('level')->nullable();
            $table->string('priority')->nullable();
            $table->float('ltv')->nullable();
            $table->string('concentrations')->nullable();
            $table->string('tracks')->nullable();
            $table->string('accreditation')->nullable();
            $table->string('online_percent')->nullable();
            $table->string('start_year')->nullable();
            $table->string('start_month')->nullable();
            $table->unsignedInteger('entry_points')->nullable();
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
