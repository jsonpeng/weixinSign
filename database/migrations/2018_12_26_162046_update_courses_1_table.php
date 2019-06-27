<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateCourses1Table extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('courses','code')){
            Schema::table('courses', function (Blueprint $table) {
             $table->string('code')->nullable()->comment('课程编码');
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
        if(Schema::hasColumn('courses','code')){
            Schema::table('courses', function (Blueprint $table) {
             $table->dropColumn('code');
            });
        }
    }
}
