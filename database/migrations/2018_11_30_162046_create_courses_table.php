<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCoursesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name')->comment('课程名称');
            $table->string('cat_name')->comment('课程分类名称');
            $table->longtext('content')->nullable()->comment('课程描述');
            $table->longtext('brief')->nullable()->comment('课程简介');

            $table->float('inside_price')->nullable()->default(0)->comment('内部员工价格');
            $table->float('price')->nullable()->default(0)->comment('普通价格');

            $table->integer('max_num')->comment('招生人数');
            $table->integer('now_num')->comment('当前已报人数');

            $table->string('activity_time')->nullable()->comment('活动时间');
            
            $table->string('sign_time')->nullable()->comment('报名时间(起)');
            $table->string('sign_time_end')->nullable()->comment('报名时间(止)');
            
            $table->integer('cat_id')->unsigned();
            $table->foreign('cat_id')->references('id')->on('course_cats');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['id', 'created_at']);
            $table->index('cat_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('courses');
    }
}
