<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveIsicNoAndTisNoColumnsFromSection5ApplicationIbcbScopesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section5_application_ibcb_scopes', function (Blueprint $table) {
            $table->dropColumn(['tis_no']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('section5_application_ibcb_scopes', function (Blueprint $table) {
            $table->text('tis_no')->nullable()->after('isic_no')->comment('เลขที่มอก. json');
        });
    }
}
