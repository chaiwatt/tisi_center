<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnCertificateIdToSection5ApplicationLabsCerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section5_application_labs_cer', function (Blueprint $table) {
            $table->integer('certificate_id')->nullable()->comment('id : certificate_exports')->after('certificate_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('section5_application_labs_cer', function (Blueprint $table) {
            $table->dropColumn([ 'certificate_id']);
        });
    }
}
