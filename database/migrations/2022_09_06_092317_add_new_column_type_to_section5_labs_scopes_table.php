<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnTypeToSection5LabsScopesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section5_labs_scopes', function (Blueprint $table) {
            $table->text('remarks')->nullable()->comment('หมายเหตุ');
            $table->integer('type')->comment('ประเภท 1 = ใบสมัคร, 2 = ระบบ labs')->default(1);
            $table->dateTime('close_state_date')->nullable()->comment('วันที่ปิดการใช้โดยระบบ');
            $table->text('close_remarks')->nullable()->comment('หมายเหตุ:ปิดการใช้งาน');
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
            $table->dropColumn(['remarks','type','close_state_date','close_remarks']);
        });
    }
}
