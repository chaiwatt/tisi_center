<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeStandardToAppCertiLabsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_certi_labs', function (Blueprint $table) {
            $table->string('type_standard')->nullable()->comment('ตามมาตรฐานเลข')->after('certificate_exports_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_certi_labs', function (Blueprint $table) {
            $table->dropColumn(['type_standard']);
        });
    }
}
