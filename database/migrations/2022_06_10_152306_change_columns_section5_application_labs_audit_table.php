<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeColumnsSection5ApplicationLabsAuditTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE section5_application_labs_audit MODIFY created_by INTEGER;");
        DB::statement("ALTER TABLE section5_application_labs_audit MODIFY updated_by INTEGER;");
        Schema::table('section5_application_labs_audit', function (Blueprint $table) {
            // $table->integer('created_by')->change();
            // $table->integer('updated_by')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('section5_application_labs_audit', function (Blueprint $table) {
            $table->text('created_by')->change();
            $table->text('updated_by')->change();
        });
    }
}
