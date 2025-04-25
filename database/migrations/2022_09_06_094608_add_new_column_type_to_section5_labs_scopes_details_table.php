<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnTypeToSection5LabsScopesDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section5_labs_scopes_details', function (Blueprint $table) {
            $table->integer('type')->comment('ประเภท 1 = ใบสมัคร, 2 = ระบบ labs')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('section5_labs_scopes_details', function (Blueprint $table) {
            $table->dropColumn(['type']);  
        });
    }
}
