<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectManagerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_managers', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('analysisSkill')->default(0);
            $table->integer('designSkill')->default(0);
            $table->integer('implementingSkill')->default(0);
            $table->integer('testingSkill')->default(0);
            $table->timestamps();
        });
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
