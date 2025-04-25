<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLawCaseBookOffendOffendToTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_case_book_offend', function (Blueprint $table) {
            $table->string('offend_to',255)->after('offend_title')->nullable()->comment('เรียน');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_case_book_offend', function (Blueprint $table) {
            $table->dropColumn(['offend_to']); 
        });
    }
}
