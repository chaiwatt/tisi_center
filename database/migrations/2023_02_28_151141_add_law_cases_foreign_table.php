<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLawCasesForeignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_cases', function (Blueprint $table) {
            $table->tinyInteger('foreign')->default(0)->comment('ต่างชาติ (ระบุเลขพาสปอร์ต) 1.ระบุ 0.ไม่ระบุ');
            $table->renameColumn('offend_ref_id', 'offend_ref_no');
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
            $table->dropColumn('other');
            $table->renameColumn('offend_ref_no', 'offend_ref_id');
        });
    }
}
