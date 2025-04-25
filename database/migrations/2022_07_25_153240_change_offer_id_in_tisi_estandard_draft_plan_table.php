<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeOfferIdInTisiEstandardDraftPlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tisi_estandard_draft_plan', function (Blueprint $table) {
            $table->unsignedInteger('offer_id')->nullable()->comment('ความคิดเห็น TB : tisi_estandard_offers . id')->change();
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
            $table->unsignedInteger('offer_id')->comment('ความคิดเห็น TB : tisi_estandard_offers . id')->change();
        });
    }
}
