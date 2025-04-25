<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOffendRefNoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_cases', function (Blueprint $table) {
            $table->string('offend_ref_no',255)->nullable()->comment('เลขที่เอกสาร (อ้างอิง)')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_cases', function (Blueprint $table) {
            $table->string('offend_ref_no',255)->nullable()->comment('เลขที่เอกสาร (อ้างอิง)')->change();
        });
    }
}
