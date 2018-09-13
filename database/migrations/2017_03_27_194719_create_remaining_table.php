<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRemainingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dependencies', function (Blueprint $table) {
            $table->integer('parentTask');
            $table->integer('childTask');
            $table->timestamps();
        });

        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('project_manager_id');
            $table->timestamps();
        });

        Schema::create('project_staffs', function (Blueprint $table) {
            $table->integer('project_id');
            $table->integer('developer_id');
            $table->timestamps();
        });

        Schema::create('files', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('task_id');
            $table->binary('file');
            $table->timestamps();
        });

        Schema::create('allocations', function (Blueprint $table) {
            $table->integer('task_id');
            $table->integer('developer_id');
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
