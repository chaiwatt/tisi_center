<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLawCasesLawyerCheckTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_cases', function (Blueprint $table) {
            $table->enum('lawyer_check', ['1', '2'])->nullable()->comment('มอบหมายงานนิติก (1.ภายใต้กลุ่มงาน, 2.กลุ่มงานอื่นๆ นิติกร)')->after('assign_at');
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
            $table->dropColumn(['lawyer_check']);
        });
    }
}
