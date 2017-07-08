<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('description');
            $table->string('category');
            $table->string('company_name');
            $table->string('company_website')->nullable();
            $table->string('company_email');
            $table->string('company_phone');
            $table->string('company_logo')->nullable();
            $table->string('company_facebook')->nullable();
            $table->string('company_video')->nullable();
            $table->string('keywords')->nullable();
            $table->string('type');
            $table->string('requirements')->nullable();
            $table->integer('user_id')->unsigned();;
            $table->dateTime('updated_at');
            $table->dateTime('created_at')->nullable();
            $table->dateTime('finish');
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->string('zone')->nullable();
            $table->string('country')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobs');
    }
}
