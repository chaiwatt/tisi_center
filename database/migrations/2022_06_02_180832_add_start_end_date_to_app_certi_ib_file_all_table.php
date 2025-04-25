<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStartEndDateToAppCertiIbFileAllTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_certi_ib_file_all', function (Blueprint $table) {
            $table->string('attach_client_name')->nullable()->comment('ชื่อไฟล์แนบ ฟิวส์ attach')->after('attach');
            $table->string('attach_pdf_client_name')->nullable()->comment('ชื่อไฟล์แนบ ฟิวส์ attach_pdf')->after('attach_pdf');
            $table->date('start_date')->nullable()->comment('วันที่เริ่ม')->after('attach_pdf_client_name');
            $table->date('end_date')->nullable()->comment('วันที่สิ้นสุด')->after('start_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_certi_ib_file_all', function (Blueprint $table) {
            $table->dropColumn(['attach_client_name','attach_pdf_client_name','start_date','end_date']);
        });
    }
}
