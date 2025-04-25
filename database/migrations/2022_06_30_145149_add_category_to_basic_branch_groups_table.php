<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCategoryToBasicBranchGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('basic_branch_groups', function (Blueprint $table) {
            $table->string('category')->nullable()->after('title')->comment('หมวดสาขา');
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
            $table->dropColumn(['category']);
        });
    }
}
