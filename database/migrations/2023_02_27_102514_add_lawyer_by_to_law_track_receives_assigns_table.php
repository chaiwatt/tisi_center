<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLawyerByToLawTrackReceivesAssignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_track_receives_assigns', function (Blueprint $table) {
            $table->integer('sub_department_id')->nullable()->comment('กลุ่มงาน/กอง sub_id ตาราง sub_department')->after('user_id');
            $table->text('lawyer_by')->nullable()->comment('นิติกรเจ้าของคดี (json) runrecno ตาราง user_register')->after('sub_department_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_track_receives_assigns', function (Blueprint $table) {
            $table->dropColumn([
                'lawyer_by',
                'sub_department_id'
            ]);
        });
    }
}
