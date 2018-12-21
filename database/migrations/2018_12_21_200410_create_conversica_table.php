<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConversicaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conversica', function (Blueprint $table) {
            $table->string('opp_id');
            $table->string('partner');
            $table->string('program');
            $table->unsignedInteger('year');
            $table->unsignedInteger('month');
            $table->unsignedInteger('day');
            $table->unsignedInteger('leads_conversica');
            $table->unsignedInteger('hot');
            $table->unsignedInteger('engaged');
            $table->date('conversica_date');
            $table->date('first_contact_date');
            $table->string('first_contact_status');
            $table->date('last_contact_date');
            $table->unsignedInteger('fc_contact');
            $table->unsignedInteger('fc_contact15');
            $table->unsignedInteger('last_contact_ac');
            $table->unsignedInteger('contact');
            $table->unsignedInteger('contact15');
            $table->unsignedInteger('quality');
            $table->unsignedInteger('quality30');
            $table->date('insales_date');
            $table->unsignedInteger('app');
            $table->date('app_date');
            $table->date('cf_date');
            $table->date('acc_date');
            $table->date('accconf_date');
            $table->unsignedInteger('starts');
            $table->string('enroll_term');
            $table->unsignedInteger('enroll_term_year');
            $table->unsignedInteger('leads');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conversica');
    }
}
