<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTisiEstandardDraftPlanRemarkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tisi_estandard_draft_plan', function (Blueprint $table) {
            $table->integer('ref_budget')->nullable()->comment('1.งบประมาณ 2.ผู้สนับสนุน');
            $table->string('budget_by', 255)->nullable()->comment('ระบุผู้สนับสนุน');
            $table->text('remark')->nullable()->comment('หมายเหตุ');
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
            $table->dropColumn(['ref_budget','budget_by','remark']);
        });
    }
}
