<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCertificateHistoryDetailsOneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('certificate_history', function (Blueprint $table) {
            $table->longText('details_one')->nullable()->comment('รายละเอียด');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('certificate_history', function (Blueprint $table) {
            $table->dropColumn(['details_one']);
        });
    }
}
