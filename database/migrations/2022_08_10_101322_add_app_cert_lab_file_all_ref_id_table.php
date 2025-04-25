<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAppCertLabFileAllRefIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_cert_lab_file_all', function (Blueprint $table) {
            $table->string('app_no',255)->nullable()->comment('เลขที่คำขอ');
            $table->text('ref_table')->nullable();
            $table->integer('ref_id')->nullable();
            $table->enum('status_cancel', ['1', '0'])->default('0')->comment('การยกเลิกการใช้งานขอบข่าย')->after('state');
            $table->integer('created_cancel')->nullable()->comment('ผู้บันทึกยกเลิก')->after('status_cancel');
            $table->datetime('date_cancel')->nullable()->comment('วันที่ยกเลิก')->after('created_cancel');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_cert_lab_file_all', function (Blueprint $table) {
            $table->dropColumn(['app_no','ref_table','ref_id','status_cancel','created_cancel','date_cancel']);
        });
    }
}
