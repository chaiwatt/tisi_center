<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCertifySetstandardMeetingRecordParticipantTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certify_setstandard_meeting_record_participant', function (Blueprint $table) {
            $table->increments('id'); 
            $table->integer('meeting_record_id')->nullable()->comment('TB : certify_setstandard_meeting_record . id');
            $table->string('name', 255)->nullable()->comment('ชื่อผู้เข้าร่วม');
            $table->integer('department_id')->nullable()->comment('TB : basic_departments . id');
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
        Schema::dropIfExists('certify_setstandard_meeting_record_participant');
    }
}
