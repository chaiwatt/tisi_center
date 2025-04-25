<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddConditionToConfigsEvidenceGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configs_evidence_groups', function (Blueprint $table) {
            $table->integer('condition')->nullable()->comment('เงื่อนไข')->after('state');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('configs_evidence_groups', function (Blueprint $table) {
            $table->dropColumn(['condition']);
        });
    }
}
