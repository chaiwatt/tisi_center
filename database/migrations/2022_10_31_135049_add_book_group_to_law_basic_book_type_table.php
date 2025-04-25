<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBookGroupToLawBasicBookTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_basic_book_type', function (Blueprint $table) {
            $table->integer('book_group_id')->nullable()->after('title')->comment('id ตาราง law_basic_book_group');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_basic_book_type', function (Blueprint $table) {
            $table->dropColumn(['book_group_id']); 
        });
    }
}
