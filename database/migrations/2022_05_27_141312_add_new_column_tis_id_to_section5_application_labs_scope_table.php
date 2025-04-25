<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnTisIdToSection5ApplicationLabsScopeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section5_application_labs_scope', function (Blueprint $table) {
            $table->integer('tis_id')->nullable()->comment('ID มอก.')->after('application_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('section5_application_labs_scope', function (Blueprint $table) {
            $table->dropColumn(['tis_id']);
        });
    }
}
