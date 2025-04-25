<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyColumnInTisiEstandardDraftBoardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tisi_estandard_draft_board', function (Blueprint $table) {

            $table->dropForeign(['committee_id']);
            $table->dropColumn('committee_id');

            $table->unsignedInteger('draft_plan_id')->nullable()->comment('TB : tisi_estandard_draft_plan . id')->after('draft_id');
            $table->unsignedInteger('offer_id')->nullable()->comment('TB : tisi_estandard_offers . id')->after('draft_plan_id');
            $table->foreign('draft_plan_id')
                  ->references('id')
                  ->on('tisi_estandard_draft_plan')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->foreign('offer_id')
                  ->references('id')
                  ->on('tisi_estandard_offers')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tisi_estandard_draft_board', function (Blueprint $table) {

            $table->unsignedInteger('committee_id')->nullable()->comment('คณะกรรมการ TB : committee_in_departments . id')->after('draft_id');
            $table->foreign('committee_id')
                  ->references('id')
                  ->on('committee_in_departments')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->dropForeign(['draft_plan_id']);
            $table->dropForeign(['offer_id']);
            $table->dropColumn(['draft_plan_id', 'offer_id']);
        });
    }
}
