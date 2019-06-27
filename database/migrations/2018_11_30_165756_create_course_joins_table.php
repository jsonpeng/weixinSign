<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCourseJoinsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_joins', function (Blueprint $table) {
            $table->increments('id');

            $table->float('price')->nullable()->comment('单价');
            $table->string('course_name')->comment('课程名称');
            $table->longtext('course_des')->nullable()->comment('课程描述');
           
            $table->enum('type',['内部员工优惠','普通'])->nullable()->default('普通')->comment('课程类型');

            $table->integer('course_id')->unsigned();
            $table->foreign('course_id')->references('id')->on('courses');
            $table->integer('order_id')->nullable()->comment('订单编号');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['id', 'created_at']);
            $table->index('course_id');
            $table->index('order_id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('course_joins');
    }
}
