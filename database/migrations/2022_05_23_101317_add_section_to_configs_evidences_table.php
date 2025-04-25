<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSectionToConfigsEvidencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configs_evidences', function (Blueprint $table) {
            $table->integer('section')->nullable()->comment('ลำดับชุดไฟล์แนบ')->after('state');
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
            $table->dropColumn(['section']);
        });
    }
}
