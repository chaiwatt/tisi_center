<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBytesToConfigsEvidencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configs_evidences', function (Blueprint $table) {
            $table->text('bytes')->nullable()->comment('ขนาดไฟล์ bytes')->after('ordering');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('configs_evidences', function (Blueprint $table) {
            $table->dropColumn(['bytes']);
        });
    }
}
