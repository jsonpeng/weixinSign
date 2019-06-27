<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrdersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');

            $table->string('number')->nullable()->comment('订单编号');
            $table->float('price')->nullable()->default(0)->comment('订单金额');
           
            
            $table->enum('pay_platform',['支付宝','微信','无'])->comment('支付平台');
            $table->enum('pay_status',['未支付','已支付','已取消'])->nullable()->comment('支付状态');

            $table->string('remark')->nullable()->comment('备注');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['id', 'created_at']);
            $table->index('user_id');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('orders');
    }
}
