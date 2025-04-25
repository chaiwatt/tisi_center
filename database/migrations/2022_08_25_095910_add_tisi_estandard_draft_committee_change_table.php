<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTisiEstandardDraftCommitteeChangeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tisi_estandard_draft_committee', function (Blueprint $table) {
            $table->unsignedInteger('committee_id')->comment('TB : committee_specials . id')->change();
            $table->dropForeign(['committee_id']);
            $table->foreign('committee_id')
                  ->references('id')
                  ->on('committee_specials')
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
        Schema::table('tisi_estandard_draft_committee', function (Blueprint $table) {
            $table->unsignedInteger('committee_id')->comment('TB : committee_in_departments . id')->change();
            $table->dropForeign(['committee_id']);
            $table->foreign('committee_id')
                  ->references('id')
                  ->on('tisi_estandard_draft')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }
}
