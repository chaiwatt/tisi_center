<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTisNoToLawBasicDepartmentStakeholderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_basic_department_stakeholder', function (Blueprint $table) {
            $table->text('tis_id')->nullable()->comment('id มอก. json')->after('title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_basic_department_stakeholder', function (Blueprint $table) {
            $table->dropColumn(['tis_id']);
        });
    }
}
