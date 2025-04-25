<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddChangeTypeToLawTrackReceivesAssignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_track_receives_assigns', function (Blueprint $table) {
            $table->string('sub_department_id')->nullable()->comment('กอง/กลุ่ม ตารางอ้างอิง sub_department')->change();
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
            $table->integer('sub_department_id')->nullable()->comment('กลุ่มงาน/กอง sub_id ตาราง sub_department')->change();
        });
    }
}
