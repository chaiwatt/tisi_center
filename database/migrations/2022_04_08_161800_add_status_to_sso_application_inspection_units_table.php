<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToSsoApplicationInspectionUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sso_application_inspection_units', function (Blueprint $table) {
            $table->integer('checking_status')->nullable()->comment('สถานะ ตรวจสอบคำขอ')->after('checking_date');
            $table->integer('approve_status')->nullable()->comment('สถานะ พิจารณาอนุมัติ')->after('approve_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sso_application_inspection_units', function (Blueprint $table) {
            $table->dropColumn(['checking_status', 'approve_status']);
        });
    }
}
