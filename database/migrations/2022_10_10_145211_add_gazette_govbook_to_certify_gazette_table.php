<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGazetteGovbookToCertifyGazetteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('certify_gazette', function (Blueprint $table) {
            $table->string('gazette_govbook')->nullable()->after('gazette_book')->comment('ที่');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('certify_gazette', function (Blueprint $table) {
            $table->dropColumn(['gazette_govbook']);  
        });
    }
}
