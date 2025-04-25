<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFkPetitioner2InAppCertiCbTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_certi_cb', function (Blueprint $table) {
            $table->dropForeign(['petitioner']);
            $table->foreign('petitioner')
                  ->references('id')
                  ->on('bcertify_certification_branches')
                  ->onDelete('NO ACTION')
                  ->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_certi_cb', function (Blueprint $table) {
            $table->dropForeign(['petitioner']);
            $table->foreign('petitioner')
                  ->references('id')
                  ->on('app_certi_cb_formulas')
                  ->onDelete('NO ACTION')
                  ->onUpdate('CASCADE');
        });
    }
}
