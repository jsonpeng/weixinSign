<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateExpertsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('experts', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name')->comment('名称');
            $table->string('image')->nullable()->comment('图像');
            $table->string('tel')->nullable()->comment('电话');
            $table->string('jiguan')->nullable()->comment('籍贯');
            $table->string('re_unit')->nullable()->comment('退休单位');
            $table->string('work_exp')->nullable()->comment('工作履历');
            $table->longtext('res_result')->nullable()->comment('研究成果');

            $table->timestamps();
            $table->softDeletes();
            $table->index(['id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('experts');
    }
}
