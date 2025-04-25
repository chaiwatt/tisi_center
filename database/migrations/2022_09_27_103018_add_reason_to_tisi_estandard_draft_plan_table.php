<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReasonToTisiEstandardDraftPlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tisi_estandard_draft_plan', function (Blueprint $table) {
            $table->text('reason')->nullable()->after('ref_document')->comment('เหตุผลและความจำเป็น');
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
            $table->dropColumn(['reason']);
        });
    }
}
