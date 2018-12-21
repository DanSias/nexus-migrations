<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesforceExportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salesforce_export', function (Blueprint $table) {
            $table->string('location');
            $table->string('bu');
            $table->string('partner');
            $table->string('pro_con');
            $table->string('program');
            $table->string('channel');
            $table->string('initiative');
            $table->string('access_code');
            $table->string('ga_tracker');
            $table->string('ga_id');
            $table->string('ga_keyword');
            $table->string('ga_misc2');
            $table->string('ga_campaign');
            $table->string('ga_source');
            $table->string('ga_misc1');
            $table->string('ga_misc3');
            $table->string('ga_medium');
            $table->string('date');
            $table->string('stage');
            $table->string('stage_detail');
            $table->string('termination_reason');
            $table->string('term_registered');
            $table->string('opp_id');
            $table->string('quality_factor');
            $table->string('contact_factor');
            $table->string('date_new_enquiry');
            $table->string('date_contact');
            $table->string('date_prospect');
            $table->string('date_app_start');
            $table->string('date_app_submit');
            $table->string('date_partner_offer');
            $table->string('date_register');
            $table->unsignedInteger('enquiry');
            $table->unsignedInteger('contact');
            $table->unsignedInteger('prospect');
            $table->unsignedInteger('app_start');
            $table->unsignedInteger('app_submit');
            $table->unsignedInteger('partner_offer');
            $table->unsignedInteger('register');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('salesforce_export');
    }
}
