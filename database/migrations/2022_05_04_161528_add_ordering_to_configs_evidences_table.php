<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderingToConfigsEvidencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configs_evidences', function (Blueprint $table) {
            $table->text('ordering')->nullable()->comment('ลำดับไฟล์แนบ')->after('size');
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
            $table->dropColumn(['ordering']);
            
        });
    }
}
