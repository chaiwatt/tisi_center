<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAppCertiLabAssessmentsAuditorIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_certi_lab_assessments', function (Blueprint $table) {
            $table->integer('auditor_id')->nullable()->after('app_certi_lab_id')->comment('ID TB :  board_auditors คณะผู้ตรวจประเมิน ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_certi_lab_assessments', function (Blueprint $table) {
            $table->dropColumn(['auditor_id']);
        });
    }
}
