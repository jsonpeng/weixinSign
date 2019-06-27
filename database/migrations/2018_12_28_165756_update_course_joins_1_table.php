<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateCourseJoins1Table extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('course_joins','join_status')){
            Schema::table('course_joins', function (Blueprint $table) {
                $table->enum('join_status',['正常参与','超额参与'])->nullable()->default('正常参与')->comment('参与类型');
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
        if(Schema::hasColumn('course_joins','join_status')){
            Schema::table('course_joins', function (Blueprint $table) {
                $table->dropColumn('join_status');
            });
        }
    }
}
