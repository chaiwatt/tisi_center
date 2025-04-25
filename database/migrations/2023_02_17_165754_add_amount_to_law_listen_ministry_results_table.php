<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAmountToLawListenMinistryResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_listen_ministry_results', function (Blueprint $table) {
            $table->integer('amount')->nullable()->comment('จำนวนวันที่มีผล');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_listen_ministry_results', function (Blueprint $table) {
            $table->dropColumn('amount');
            
        });
    }
}
