<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLawCasesCloseByTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_cases', function (Blueprint $table) {
            $table->integer('close_by')->nullable()->after('accept_at')->comment('ผู้ปิดงานคดี  runrecno ตาราง user_register');
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
            $table->dropColumn(['close_by']);
        });
    }
}
