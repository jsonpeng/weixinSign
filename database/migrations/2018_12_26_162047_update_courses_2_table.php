<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateCourses2Table extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('courses','show')){
            Schema::table('courses', function (Blueprint $table) {
             $table->string('show')->nullable()->default(1)->comment('上架1 下架0');
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
    }
}
