<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadingIndicatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leading_indicators', function (Blueprint $table) {
            $table->string('starbuckscoreflag');
            $table->string('gradugrdflag');
            $table->string('programname');
            $table->unsignedInteger('bu');
            $table->string('program');
            $table->unsignedInteger('yearmonth');
            $table->unsignedInteger('term_year');
            $table->string('term_code');
            $table->string('college');
            $table->string('advisor');
            $table->string('admmanager');
            $table->string('secondmanager');
            $table->string('opportunitystage');
            $table->string('opportunitystatus');
            $table->string('status');
            $table->unsignedInteger('backlog_days_current_status');
            $table->unsignedInteger('backlog_records');
            $table->unsignedInteger('backlog_team_days_current_status');
            $table->unsignedInteger('backlog_team_records');
            $table->unsignedInteger('backlog_second_mgr_days_current_status');
            $table->unsignedInteger('backlog_second_mgr_records');
            $table->unsignedInteger('hist_days');
            $table->unsignedInteger('hist_records');
            $table->unsignedInteger('hist_days_team');
            $table->unsignedInteger('hist_records_team');
            $table->unsignedInteger('hist_days_second_mgr');
            $table->unsignedInteger('hist_records_second_mgr');
            $table->unsignedInteger('leads');
            $table->unsignedInteger('leads_team');
            $table->unsignedInteger('eas_team');
            $table->unsignedInteger('leads_second_mgr');
            $table->unsignedInteger('eas_second_mgr');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leading_indicators');
    }
}
