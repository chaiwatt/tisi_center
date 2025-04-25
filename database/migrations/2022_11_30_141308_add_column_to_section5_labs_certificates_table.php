<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToSection5LabsCertificatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section5_labs_certificates', function (Blueprint $table) {
            $table->dropColumn(['renew_certificate_no', 'renew_certificate_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('section5_labs_certificates', function (Blueprint $table) {
            $table->string('renew_certificate_no')->nullable()->comment('เลขที่ใบรับรองเดิม');
            $table->integer('renew_certificate_id')->nullable()->comment('id : certificate_exports');
        });
    }
}
