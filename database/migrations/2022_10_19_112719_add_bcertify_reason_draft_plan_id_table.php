<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBcertifyReasonDraftPlanIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bcertify_reason', function (Blueprint $table) {
            $table->integer('draft_plan_id')->nullable()->comment('TB : tisi_estandard_draft_plan . id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bcertify_reason', function (Blueprint $table) {
            $table->dropColumn(['draft_plan_id']);  
        });
    }
}
