<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCloseDateToLawListenMinistryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_listen_ministry', function (Blueprint $table) {
            $table->dropColumn(['state_listen']);
            $table->date('close_date')->nullable()->comment('วันที่ปิดงาน')->after('status_close');
            $table->integer('close_by')->nullable()->comment('ผู้ปิดงาน')->after('close_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_listen_ministry', function (Blueprint $table) {
            $table->integer('state_listen')->nullable()->comment('สถานะเปิดใช้งานความเห็น : 1 = เปิดใช้งาน')->after('status_close');
            $table->dropColumn(['close_date','close_by']);
        });
    }
}
