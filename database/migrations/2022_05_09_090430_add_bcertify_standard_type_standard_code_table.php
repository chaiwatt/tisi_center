<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBcertifyStandardTypeStandardCodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bcertify_standard_type', function (Blueprint $table) {
            $table->string('standard_code',255)->nullable()->comment('รหัสมาตรฐาน')->after('title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bcertify_standard_type', function (Blueprint $table) {
            $table->dropColumn(['standard_code']);
        });
    }
}
