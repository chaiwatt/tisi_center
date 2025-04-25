<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeBranchIdInBsection5WorkgroupIbBranchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bsection5_workgroup_ib_branch', function (Blueprint $table) {
            $table->renameColumn('branch_id', 'branch_group_id');
        });

        Schema::table('bsection5_workgroup_ib_branch', function (Blueprint $table) {
            $table->integer('branch_group_id')->nullable()->comment('id ตาราง basic_branch_groups')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bsection5_workgroup_ib_branch', function (Blueprint $table) {
            $table->renameColumn('branch_group_id', 'branch_id');
        });
        
        Schema::table('bsection5_workgroup_ib_branch', function (Blueprint $table) {
            $table->integer('branch_id')->nullable()->comment('id ตาราง basic_branches')->change();
        });
    }
}
