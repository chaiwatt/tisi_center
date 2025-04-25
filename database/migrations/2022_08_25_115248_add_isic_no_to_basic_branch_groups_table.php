<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsicNoToBasicBranchGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('basic_branch_groups', function (Blueprint $table) {
            $table->string('isic_no')->nullable()->after('category')->comment('ISIC No.');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('basic_branch_groups', function (Blueprint $table) {
            $table->dropColumn(['isic_no']);
        });
    }
}
