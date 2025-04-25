<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddImportantToLawBookManageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_book_manage', function (Blueprint $table) {
            $table->string('important')->nullable()->after('title')->comment('ใจความสำคัญ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_book_manage', function (Blueprint $table) {
            $table->dropColumn(['important']); 
        });
    }
}
