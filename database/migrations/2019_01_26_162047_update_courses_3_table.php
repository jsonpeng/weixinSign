<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateCourses3Table extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('courses','open_status')){
            Schema::table('courses', function (Blueprint $table) {
             $table->string('open_status')->nullable()->default('开放')->comment('开放 | 关闭');
            });
        }
        if(!Schema::hasColumn('courses','end_time')){
            Schema::table('courses', function (Blueprint $table) {
                $table->string('end_time')->nullable()->default('2019-12-29 09:41')->comment('课程截止时间');
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
        if(Schema::hasColumn('courses','show')){
            Schema::table('courses', function (Blueprint $table) {
             $table->dropColumn('show');
            });
        }
        if(Schema::hasColumn('courses','end_time')){
            Schema::table('courses', function (Blueprint $table) {
                $table->dropColumn('end_time');
            });
        }
    }
}
