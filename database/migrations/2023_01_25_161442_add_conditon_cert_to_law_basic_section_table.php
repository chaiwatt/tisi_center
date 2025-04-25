<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddConditonCertToLawBasicSectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_basic_section', function (Blueprint $table) {
            $table->integer('conditon_cert')->nullable()->comment('1=มีใบอนุญาติ')->after('section_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_basic_section', function (Blueprint $table) {
            $table->dropColumn(['conditon_cert']);
        });
    }
}
