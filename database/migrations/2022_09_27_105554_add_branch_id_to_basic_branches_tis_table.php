<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBranchIdToBasicBranchesTisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('basic_branches_tis', function (Blueprint $table) {
            $table->unsignedInteger('branch_id')->nullable()->after('branch_groups_id')->comment('id ตาราง basic_branches');
            $table->foreign('branch_id')
                  ->references('id')
                  ->on('basic_branches')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('basic_branches_tis', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn(['branch_id']);
        });
    }
}
