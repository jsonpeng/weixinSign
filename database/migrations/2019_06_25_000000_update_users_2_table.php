<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUsers2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('users','can_read_zj'))
        {
            Schema::table('users', function (Blueprint $table) {
                $table->string('can_read_zj')->nullable()->comment('是否可以看专家资料');
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
        if(Schema::hasColumn('users','can_read_zj'))
        {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('can_read_zj');
            });
        }
    }
}
