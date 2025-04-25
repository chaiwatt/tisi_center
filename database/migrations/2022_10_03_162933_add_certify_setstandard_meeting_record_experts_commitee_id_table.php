<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCertifySetstandardMeetingRecordExpertsCommiteeIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('certify_setstandard_meeting_record_experts', function (Blueprint $table) {
            $table->integer('commitee_id')->nullable()->comment('id ตาราง bcertify_committee_specials');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('certify_setstandard_meeting_record_experts', function (Blueprint $table) {
            $table->dropColumn(['commitee_id']);
        });
    }
}
