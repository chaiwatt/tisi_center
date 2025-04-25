<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUrlToConfigsEvidenceGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configs_evidence_groups', function (Blueprint $table) {
            $table->text('url')->nullable()->comment('ระบบที่นำไปใช้/Url')->after('ordering');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('configs_evidence_groups', function (Blueprint $table) {
            $table->dropColumn(['url']);
            
        });
    }
}
