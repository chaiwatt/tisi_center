<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewDeparmentToLawTrackReceivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_track_receives', function (Blueprint $table) {
            $table->integer('law_deperment_type')->nullable()->comment('ประเภทหน่วยงาน : 1 หน่วยงานภายใน (สมอ.), 2 หน่วยงานภายนอก')->after('receive_time');
            $table->integer('sub_departments_id')->nullable()->comment('ID : sub_department')->after('law_bs_deperment_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_track_receives', function (Blueprint $table) {
            $table->dropColumn(['law_deperment_type','sub_departments_id']); 
            
        });
    }
}
