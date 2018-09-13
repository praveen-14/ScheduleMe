<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GanttChart extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('gantt_links', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('source');
            $table->integer('target');
            $table->string('type');
        });
        Schema::create('gantt_tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('text');
            $table->dateTime('start_date');
            $table->integer('duration')->default(0);
            $table->float('progress', 2, 1);
            $table->integer('sortorder')->default(0);
            $table->integer('parent');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gantt_links');
        Schema::dropIfExists('gantt_tasks');

    }
}
