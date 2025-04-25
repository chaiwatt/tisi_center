<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTisiEstandardDraftPlanPeriodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tisi_estandard_draft_plan', function (Blueprint $table) {
            $table->string('period',10)->nullable()->comment('ระยะเวลา (เดือน)')->after('plan_enddate');
            $table->string('budget',255)->nullable()->comment('งบประมาณ (บาท)')->after('period');
            $table->text('confirm_detail')->nullable()->comment('รายละเอียดการพิจารณา')->after('budget');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tisi_estandard_draft_plan', function (Blueprint $table) {
             $table->dropColumn(['period','budget','confirm_detail']);
        });
    }
}
