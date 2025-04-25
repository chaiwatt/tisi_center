<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAcceptToSection5ApplicationInspectorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section5_application_inspectors', function (Blueprint $table) {
            $table->date('accept_date')->nullable()->comment('วันที่รับคำขอ');
            $table->integer('accept_by')->nullable()->comment('ผู้รับคำขอ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('section5_application_inspectors', function (Blueprint $table) {
            $table->dropColumn(['accept_date', 'accept_by']);
        });
    }
}
