<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCourseCatsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_cats', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name')->comment('分类名称');

            $table->enum('type',['课程班','兴趣小组','活动'])->nullable()->default('课程班')->comment('分类类型');

            $table->string('image')->nullable()->comment('分类图片');
            $table->integer('pid')->nullable()->default(0)->comment('父id');
            $table->string('content')->nullable()->comment('分类描述');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['id', 'created_at']);
            $table->index('type');
            $table->index('pid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('course_cats');
    }
}
