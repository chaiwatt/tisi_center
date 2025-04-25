<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCertifySetstandardMeetingRecordAmountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('certify_setstandard_meeting_record', function (Blueprint $table) {
            $table->decimal('amount',15,2)->nullable()->comment('จำนวนเงินเบี้ยการประชุม')->after('status_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('certify_setstandard_meeting_record', function (Blueprint $table) {
            $table->dropColumn(['amount']);  
        });
    }
}
