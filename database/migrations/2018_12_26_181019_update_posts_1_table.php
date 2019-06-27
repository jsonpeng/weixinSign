<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdatePosts1Table extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('posts','cat_name')){
            Schema::table('posts', function (Blueprint $table) {

                $table->string('cat_name')->nullable()->default('')->comment('分类名称');
               
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
        if(Schema::hasColumn('posts','cat_name')){
            Schema::table('posts', function (Blueprint $table) {

                $table->dropColumn('cat_name');
               
            });
        }
       
    }
}
