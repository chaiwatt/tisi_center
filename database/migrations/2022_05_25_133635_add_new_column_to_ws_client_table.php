<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnToWsClientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ws_client', function (Blueprint $table) {
            $table->string('phone')->nullable()->comment('เบอร์โทรผู้ติดต่อ');
            $table->text('file')->nullable()->comment('ไฟล์');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ws_client', function (Blueprint $table) {
            $table->dropColumn(['phone', 'file']);
        });
    }
}
