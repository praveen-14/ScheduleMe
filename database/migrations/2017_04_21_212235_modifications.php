<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Modifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->integer('estimatedTime')->default(0)->change();
            $table->integer('startTime')->default(0)->change();
//            $table->dropColumn('endTime');
//            $table->dropColumn('acceptedTime');
//            $table->dropColumn('submittedTime');
        });
        Schema::table('project_managers', function (Blueprint $table) {
            $table->integer('analysisSkill')->default(5)->change();
            $table->integer('designSkill')->default(5)->change();
            $table->integer('implementingSkill')->default(5)->change();
            $table->integer('testingSkill')->default(5)->change();
        });
        Schema::table('developers', function (Blueprint $table) {
            $table->integer('analysisSkill')->default(5)->change();
            $table->integer('designSkill')->default(5)->change();
            $table->integer('implementingSkill')->default(5)->change();
            $table->integer('testingSkill')->default(5)->change();
        });
        Schema::table('projects', function (Blueprint $table) {
            $table->string('startTime')->nullable();
        });
        Schema::table('tasks', function (Blueprint $table) {
            $table->string('acceptedTime')->nullable();
            $table->string('submittedTime')->nullable();
        });

        //
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
