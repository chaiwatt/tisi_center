<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddConfigsEvidenceToSection5ApplicationIbcbTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section5_application_ibcb', function (Blueprint $table) {
            $table->text('config_evidencce')->nullable()->comment('ตั้งค่าไฟล์แนบ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('section5_application_ibcb', function (Blueprint $table) {
            $table->dropColumn(['config_evidencce']);
        });
    }
}
