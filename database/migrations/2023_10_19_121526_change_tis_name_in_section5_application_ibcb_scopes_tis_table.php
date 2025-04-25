<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTisNameInSection5ApplicationIbcbScopesTisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section5_application_ibcb_scopes_tis', function (Blueprint $table) {
            $table->string('tis_name', 1024)->nullable()->comment('ชื่อ มอก.')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('section5_application_ibcb_scopes_tis', function (Blueprint $table) {
            $table->string('tis_name')->nullable()->comment('เลข มอก.')->change();
        });
    }
}
