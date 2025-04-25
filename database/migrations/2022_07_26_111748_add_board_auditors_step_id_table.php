<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBoardAuditorsStepIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('board_auditors', function (Blueprint $table) {
            $table->integer('step_id')->nullable()->comment('TB : app_certi_lab_auditors_step');
            $table->string('auditor',255)->nullable()->comment('ชื่อคณะผู้ตรวจประเมิน');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('board_auditors', function (Blueprint $table) {
            $table->dropColumn(['step_id','auditor']);
        });
    }
}
