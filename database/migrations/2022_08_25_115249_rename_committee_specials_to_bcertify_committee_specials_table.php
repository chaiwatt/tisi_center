<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameCommitteeSpecialsToBcertifyCommitteeSpecialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('committee_specials', 'bcertify_committee_specials');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bcertify_committee_specials', function (Blueprint $table) {
            Schema::rename('bcertify_committee_specials', 'committee_specials');
        });
    }
}
