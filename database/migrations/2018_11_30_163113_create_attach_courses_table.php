<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAttachCoursesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attach_courses', function (Blueprint $table) {
            $table->increments('id');

            $table->string('weekday')->comment('星期几 一到日');
            $table->string('start_time')->comment('开始时间');
            $table->string('end_time')->comment('结束时间');
            $table->string('classroom_name')->comment('教室名称');
            $table->string('teacher_name')->comment('老师名称');

            $table->integer('course_id')->unsigned();
            $table->foreign('course_id')->references('id')->on('courses');

            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['id', 'created_at']);
            $table->index('course_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('attach_courses');
    }
}
