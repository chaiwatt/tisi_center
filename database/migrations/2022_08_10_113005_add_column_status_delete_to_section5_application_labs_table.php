<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnStatusDeleteToSection5ApplicationLabsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section5_application_labs', function (Blueprint $table) {
            $table->integer('delete_state')->nullable()->comment('สถานะลบข้อมูล');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('section5_application_labs', function (Blueprint $table) {
            $table->dropColumn(['delete_state']);
        });
    }
}
