<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBookNumberToLawCaseBookOffendTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_case_book_offend', function (Blueprint $table) {
            $table->text('book_number')->nullable()->comment('เลขที่หนังสือ');
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
                'book_number'
            ]);
        });
    }
}
