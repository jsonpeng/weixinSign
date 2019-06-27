<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateCourses4Table extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('courses','course_end_time')){
            Schema::table('courses', function (Blueprint $table) {
                $table->string('course_end_time')->nullable()->default('2019-12-29 09:41')->comment('课程截止时间');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasColumn('courses','course_end_time')){
            Schema::table('courses', function (Blueprint $table) {
                $table->dropColumn('course_end_time');
            });
        }
    }
}
