<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditLawBasicSectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_basic_section', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'end_date']);
            $table->date('date_announce')->nullable()->after('remark')->comment('วันที่ประกาศ');
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
            $table->date('start_date')->nullable()->comment('วันที่เริ่มใช้งาน');
            $table->date('end_date')->nullable()->comment('วันที่สิ้นสุดใช้งาน');
            $table->dropColumn(['date_announce']);
        });
    }
}
