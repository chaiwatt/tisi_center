<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRemarkToLawCaseLevelApprovesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_case_level_approves', function (Blueprint $table) {
            $table->text('remark')->nullable()->after('authorize_userid')->comment('ความคิดเห็น');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_case_level_approves', function (Blueprint $table) {
            $table->dropColumn(['remark']);
        });
    }
}
