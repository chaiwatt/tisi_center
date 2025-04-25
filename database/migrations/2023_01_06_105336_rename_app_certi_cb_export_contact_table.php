<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameAppCertiCbExportContactTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_certi_cb_export', function (Blueprint $table) {
            $table->renameColumn('contact_moblie', 'contact_mobile');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_certi_cb_export', function (Blueprint $table) {
            $table->renameColumn('contact_mobile', 'contact_moblie');
        });
    }
}
