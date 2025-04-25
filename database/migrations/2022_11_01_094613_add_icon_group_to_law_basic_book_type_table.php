<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIconGroupToLawBasicBookTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_basic_book_type', function (Blueprint $table) {
            $table->string('icons')->nullable()->after('book_group_id')->comment('icons');
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
            $table->dropColumn(['icons']); 
        });
    }
}
