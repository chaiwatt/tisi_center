<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCertifySetstandardMeetingTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certify_setstandard_meeting_type', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('setstandard_id')->nullable()->comment('ID certify_setstandards . id');
            $table->integer('setstandard_meeting_id')->nullable()->comment('TB : certify_setstandard_meeting . id');
            $table->integer('meetingtype_id')->nullable()->comment('TB : bcertify_meetingtype . id');
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
        Schema::dropIfExists('certify_setstandard_meeting_type');
    }
}
