<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAuditorInformationsCheckboxConfirmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('auditor_informations', function (Blueprint $table) {
            $table->integer('checkbox_confirm')->nullable()->comment('1=ยอมรับเงื่อนไข')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('auditor_informations', function (Blueprint $table) {
            $table->dropColumn(['checkbox_confirm']);
        });
    }
}
