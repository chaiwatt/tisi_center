<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToTisiEstandardDraftPlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tisi_estandard_draft_plan', function (Blueprint $table) {
            $table->tinyInteger('start_std')->nullable()->after('std_type')->comment('การกำหนดมาตรฐาน');
            $table->integer('ref_std')->nullable()->after('start_std')->comment('มาตรฐาน');
            $table->string('ref_document', 255)->nullable()->after('method_id')->comment('เอกสารอ้างอิง');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tisi_estandard_draft_plan', function (Blueprint $table) {
            $table->dropColumn(['start_std','ref_std','ref_document']);
        });
    }
}
