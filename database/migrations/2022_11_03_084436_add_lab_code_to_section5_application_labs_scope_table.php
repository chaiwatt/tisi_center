<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLabCodeToSection5ApplicationLabsScopeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section5_application_labs_scope', function (Blueprint $table) {
            $table->integer('lab_id')->nullable()->comment('ID : รหัสปฏิบัติการ ตาราง section5_labs');
            $table->string('lab_code', 255)->nullable()->comment('ห้องปฏิบัติการ: รหัสปฏิบัติการ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('section5_application_labs_scope', function (Blueprint $table) {
            $table->dropColumn(['lab_id','lab_code']);
        });
    }
}
