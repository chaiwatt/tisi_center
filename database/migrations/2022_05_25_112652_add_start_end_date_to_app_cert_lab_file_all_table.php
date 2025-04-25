<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStartEndDateToAppCertLabFileAllTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_cert_lab_file_all', function (Blueprint $table) {
            $table->date('start_date')->nullable()->comment('วันที่เริ่ม')->after('attach_pdf');
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
        Schema::table('app_cert_lab_file_all', function (Blueprint $table) {
            $table->dropColumn(['start_date','end_date']);
        });
    }
}
