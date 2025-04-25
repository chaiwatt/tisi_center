<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeetingStandardRecordExpertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certify_setstandard_meeting_record_experts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('meeting_record_id')->nullable()->comment('id ตาราง certify_setstandard_meeting_record');
            $table->integer('experts_id')->nullable()->comment('id ตาราง register_experts'); 
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('certify_setstandard_meeting_record_experts');
    }
}
