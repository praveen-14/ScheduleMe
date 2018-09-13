<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Timemodify extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('startTime');
            $table->date('startingTime')->nullable();
            $table->date('endingTime')->nullable();
//            $table->dropColumn('endTime');
//            $table->dropColumn('acceptedTime');
//            $table->dropColumn('submittedTime');
        });
        Schema::table('allocations', function (Blueprint $table) {
            $table->date('acceptedTime')->nullable();
            $table->date('submittedTime')->nullable();
//            $table->dropColumn('endTime');
//            $table->dropColumn('acceptedTime');
//            $table->dropColumn('submittedTime');
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
