<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateClassroomsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classrooms', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name')->comment('教室名称');
            $table->string('location')->nullable()->comment('经纬度位置');
            $table->string('address')->nullable()->comment('地址');

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
        Schema::drop('classrooms');
    }
}
