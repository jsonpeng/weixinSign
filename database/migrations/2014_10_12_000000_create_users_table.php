<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            
            $table->string('name')->nullable()->comment('姓名');
            $table->string('head_image')->nullable()->comment('头像');
            $table->string('nickname')->nullable()->comment('昵称');
            $table->string('mobile')->nullable()->comment('手机');
            $table->string('openid')->nullable()->comment('微信OPEN ID');
            $table->string('unionid')->nullable()->comment('公众平台ID');
            $table->string('idcard_num')->nullable()->comment('身份证号码');

            $table->timestamp('last_login')->nullable()->comment('最后登录日期');
            $table->string('last_ip')->nullable()->comment('最后登录IP');

            $table->enum('type',['单位内部用户','普通用户'])->nullable()->default('普通用户')->comment('用户类型');
            $table->string('ret_unit')->nullable()->comment('退休单位');

            $table->enum('import_type',['导入用户','微信用户'])->nullable()->default('微信用户')->comment('导入用户类型');
            $table->enum('import_status',['已导入','未导入'])->nullable()->default('未导入')->comment('导入用户状态');

            $table->index(['id', 'created_at']);

            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
