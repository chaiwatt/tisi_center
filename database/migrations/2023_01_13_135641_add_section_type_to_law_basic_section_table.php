<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSectionTypeToLawBasicSectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_basic_section', function (Blueprint $table) {
            $table->integer('section_type')->nullable()->comment('ประเภทมาตราความผิด 1 = ฝ่าฝืน 2 = อัตราโทษ')->after('date_announce');
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
            $table->dropColumn(['section_type']);
        });
    }
}
