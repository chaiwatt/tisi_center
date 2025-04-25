<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBasicExpertGroupsBcertifyCommitteeSpecialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bcertify_committee_specials', function (Blueprint $table) {
            $table->integer('expert_group_id')->nullable()->comment('idตาราง : basic_expert_groups')->after('committee_group');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bcertify_committee_specials', function (Blueprint $table) {
            $table->dropColumn(['expert_group_id']);
        });
    }
}
