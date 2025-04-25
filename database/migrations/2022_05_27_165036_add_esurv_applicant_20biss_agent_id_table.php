<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEsurvApplicant20bissAgentIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('esurv_applicant_20biss', function (Blueprint $table) {
            $table->integer('agent_id')->after('created_by')->nullable()->comment('id ตาราง sso_users ผู้รับมอบอำนาจที่ดำเนินการแทน');
        });
        Schema::table('esurv_applicant_20ters', function (Blueprint $table) {
            $table->integer('agent_id')->after('created_by')->nullable()->comment('id ตาราง sso_users ผู้รับมอบอำนาจที่ดำเนินการแทน');
        });
        Schema::table('esurv_applicant_21biss', function (Blueprint $table) {
            $table->integer('agent_id')->after('created_by')->nullable()->comment('id ตาราง sso_users ผู้รับมอบอำนาจที่ดำเนินการแทน');
        });
        Schema::table('esurv_applicant_21owns', function (Blueprint $table) {
            $table->integer('agent_id')->after('created_by')->nullable()->comment('id ตาราง sso_users ผู้รับมอบอำนาจที่ดำเนินการแทน');
        });
        Schema::table('esurv_applicant_21ters', function (Blueprint $table) {
            $table->integer('agent_id')->after('created_by')->nullable()->comment('id ตาราง sso_users ผู้รับมอบอำนาจที่ดำเนินการแทน');
        });
        Schema::table('esurv_inform_calibrates', function (Blueprint $table) {
            $table->integer('agent_id')->after('created_by')->nullable()->comment('id ตาราง sso_users ผู้รับมอบอำนาจที่ดำเนินการแทน');
        });
        Schema::table('esurv_inform_changes', function (Blueprint $table) {
            $table->integer('agent_id')->after('created_by')->nullable()->comment('id ตาราง sso_users ผู้รับมอบอำนาจที่ดำเนินการแทน');
        });
        Schema::table('esurv_inform_inspections', function (Blueprint $table) {
            $table->integer('agent_id')->after('created_by')->nullable()->comment('id ตาราง sso_users ผู้รับมอบอำนาจที่ดำเนินการแทน');
        });
        Schema::table('esurv_inform_quality_controls', function (Blueprint $table) {
            $table->integer('agent_id')->after('created_by')->nullable()->comment('id ตาราง sso_users ผู้รับมอบอำนาจที่ดำเนินการแทน');
        });
        Schema::table('esurv_inform_volumes', function (Blueprint $table) {
            $table->integer('agent_id')->after('created_by')->nullable()->comment('id ตาราง sso_users ผู้รับมอบอำนาจที่ดำเนินการแทน');
        });
        Schema::table('tisi_license_notifications', function (Blueprint $table) {
            $table->integer('agent_id')->after('created_by')->nullable()->comment('id ตาราง sso_users ผู้รับมอบอำนาจที่ดำเนินการแทน');
        });
        Schema::table('esurv_others', function (Blueprint $table) {
            $table->integer('agent_id')->after('created_by')->nullable()->comment('id ตาราง sso_users ผู้รับมอบอำนาจที่ดำเนินการแทน');
        });
        Schema::table('esurv_volume_20biss', function (Blueprint $table) {
            $table->integer('agent_id')->after('created_by')->nullable()->comment('id ตาราง sso_users ผู้รับมอบอำนาจที่ดำเนินการแทน');
        });
        Schema::table('esurv_volume_20ters', function (Blueprint $table) {
            $table->integer('agent_id')->after('created_by')->nullable()->comment('id ตาราง sso_users ผู้รับมอบอำนาจที่ดำเนินการแทน');
        });
        Schema::table('esurv_volume_21biss', function (Blueprint $table) {
            $table->integer('agent_id')->after('created_by')->nullable()->comment('id ตาราง sso_users ผู้รับมอบอำนาจที่ดำเนินการแทน');
        });
        Schema::table('esurv_volume_21owns', function (Blueprint $table) {
            $table->integer('agent_id')->after('created_by')->nullable()->comment('id ตาราง sso_users ผู้รับมอบอำนาจที่ดำเนินการแทน');
        });
        Schema::table('esurv_volume_21ters', function (Blueprint $table) {
            $table->integer('agent_id')->after('created_by')->nullable()->comment('id ตาราง sso_users ผู้รับมอบอำนาจที่ดำเนินการแทน');
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('esurv_applicant_20biss', function (Blueprint $table) {
            $table->dropColumn(['agent_id']);
        });
        Schema::table('esurv_applicant_20ters', function (Blueprint $table) {
            $table->dropColumn(['agent_id']);
        });
        Schema::table('esurv_applicant_21biss', function (Blueprint $table) {
            $table->dropColumn(['agent_id']);
        });
        Schema::table('esurv_applicant_21owns', function (Blueprint $table) {
            $table->dropColumn(['agent_id']);
        });
        Schema::table('esurv_applicant_21ters', function (Blueprint $table) {
            $table->dropColumn(['agent_id']);
        });
        Schema::table('esurv_inform_calibrates', function (Blueprint $table) {
            $table->dropColumn(['agent_id']);
        });
        Schema::table('esurv_inform_changes', function (Blueprint $table) {
            $table->dropColumn(['agent_id']);
        });
        Schema::table('esurv_inform_inspections', function (Blueprint $table) {
            $table->dropColumn(['agent_id']);
        });
        Schema::table('esurv_inform_quality_controls', function (Blueprint $table) {
            $table->dropColumn(['agent_id']);
        });
        Schema::table('esurv_inform_volumes', function (Blueprint $table) {
            $table->dropColumn(['agent_id']);
        });
        Schema::table('tisi_license_notifications', function (Blueprint $table) {
            $table->dropColumn(['agent_id']);
        });
        Schema::table('esurv_others', function (Blueprint $table) {
            $table->dropColumn(['agent_id']);
        });
        Schema::table('esurv_volume_20biss', function (Blueprint $table) {
            $table->dropColumn(['agent_id']);
        });
        Schema::table('esurv_volume_20ters', function (Blueprint $table) {
            $table->dropColumn(['agent_id']);
        });
        Schema::table('esurv_volume_21biss', function (Blueprint $table) {
            $table->dropColumn(['agent_id']);
        });
        Schema::table('esurv_volume_21owns', function (Blueprint $table) {
            $table->dropColumn(['agent_id']);
        });
        Schema::table('esurv_volume_21ters', function (Blueprint $table) {
            $table->dropColumn(['agent_id']);
        });
    }
}
