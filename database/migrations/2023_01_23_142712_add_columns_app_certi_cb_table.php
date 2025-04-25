<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsAppCertiCbTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_certi_cb', function (Blueprint $table) {
            $table->integer('deleted_by')->nullable()->after('desc_delete')->comment('ผู้ยกเลิก');
            $table->dateTime('deleted_at')->nullable()->after('deleted_by')->comment('วันที่ยกเลิก');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_certi_cb', function (Blueprint $table) {
            $table->dropColumn(['deleted_by', 'deleted_at']); 
        });
    }
}
