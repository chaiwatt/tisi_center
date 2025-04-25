<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAssignToSsoApplicationInspectionUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sso_application_inspection_units', function (Blueprint $table) {
            $table->integer('assign_by')->nullable()->comment('ผู้ได้รับมอบหมาย')->after('approve_date');
            $table->dateTime('assign_date')->nullable()->comment('วันที่มอบหมาย')->after('assign_by');
            $table->text('assign_comment')->nullable()->comment('ความคิดเห็นเพิ่มเติมมอบหมาย')->after('assign_date');
    
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
            $table->dropColumn(['assign_by', 'assign_date', 'assign_comment']);
        });
    }
}
