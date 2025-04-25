<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTisiEstandardOffersRefnoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tisi_estandard_offers', function (Blueprint $table) {
            $table->string('refno',255)->nullable()->comment('รหัสความเห็น')->after('standard_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tisi_estandard_offers', function (Blueprint $table) {
            $table->dropColumn(['refno']);
        });
    }
}
