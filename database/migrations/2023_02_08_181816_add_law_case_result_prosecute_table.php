<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLawCaseResultProsecuteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_case_result', function (Blueprint $table) {
            $table->enum('prosecute', ['0', '1'])->default('0')->comment('ดำเนินคดี (ผลิตภัณฑ์) 0.ไม่ได้ดำเนินคดี, 1.ดำเนินคดี')->after('product');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_case_result', function (Blueprint $table) {
            $table->dropColumn([
                               'prosecute'
                           ]);
        });
    }
}
