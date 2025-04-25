<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdUnitToTb3TisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tb3_tis', function (Blueprint $table) {
            $table->string('id_unit')->after('unitcode_id')->comment('รหัสหน่วย จากกรมศุล');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tb3_tis', function (Blueprint $table) {
            $table->dropColumn(['id_unit']);
        });
    }
}
