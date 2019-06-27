<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateCourseJoins2Table extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('course_joins','join_year'))
        {
            Schema::table('course_joins', function (Blueprint $table) {
                $table->integer('join_year')->nullable()->default('2019')->comment('参与年代');
            });
        }

        if(!Schema::hasColumn('course_joins','join_quarter'))
        {
            Schema::table('course_joins', function (Blueprint $table) {
                $table->integer('join_quarter')->nullable()->default('1')->comment('参与季度1春季 2秋季');
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

        if(Schema::hasColumn('course_joins','join_year'))
        {
            Schema::table('course_joins', function (Blueprint $table) {
                $table->dropColumn('join_year');
            });
        }
        
        if(Schema::hasColumn('course_joins','join_quarter'))
        {
            Schema::table('course_joins', function (Blueprint $table) {
                $table->dropColumn('join_quarter');
            });
        }
    }
}
