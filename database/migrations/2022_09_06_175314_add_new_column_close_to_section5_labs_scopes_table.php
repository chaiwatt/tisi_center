<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnCloseToSection5LabsScopesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section5_labs_scopes', function (Blueprint $table) {
            $table->dateTime('close_date')->nullable()->comment('วันที่ปิดการใช้งาน');
            $table->integer('close_by')->nullable()->comment('ผู้ปิดการใช้งาน');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('section5_labs_scopes', function (Blueprint $table) {
            $table->dropColumn(['close_date','close_by']);
        });
    }
}
