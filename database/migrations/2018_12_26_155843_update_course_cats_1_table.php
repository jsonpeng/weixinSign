<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateCourseCats1Table extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('course_cats','show')){
            Schema::table('course_cats', function (Blueprint $table) {
                $table->integer('show')->nullable()->default(1)->comment('上架1 下架0');
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
        if(Schema::hasColumn('course_cats','show')){
            Schema::table('course_cats', function (Blueprint $table) {
                $table->dropColumn('show');
            });
        }
    }
}
