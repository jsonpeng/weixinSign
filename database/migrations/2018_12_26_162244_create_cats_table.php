<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCatsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cats', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name')->comment('文案分类名称');
            $table->integer('sort')->nullable()->default(0)->comment('排序权重 越高排序越靠前');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['id', 'created_at']);
            $table->index('sort');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('cats');
    }
}
