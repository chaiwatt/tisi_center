<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOffendFoundToLawCaseBookOffendTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_case_book_offend', function (Blueprint $table) {
            $table->text('offend_found')->nullable()->comment('พบการกระทำความ');
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
            $table->dropColumn([
                'offend_found'
            ]);
        });
    }
}
