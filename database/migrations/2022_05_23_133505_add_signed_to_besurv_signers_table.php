<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSignedToBesurvSignersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('besurv_signers', function (Blueprint $table) {
            $table->string('tax_number',15)->nullable()->after('id')->comment('เลข 13 หลัก ผู้ประกอบการ');
            $table->string('line_token')->nullable()->after('main_group');
            $table->integer('signed')->nullable()->after('line_token');
            $table->string('attach')->nullable()->after('signed')->comment('ไฟล์แนบ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('besurv_signers', function (Blueprint $table) {
            $table->dropColumn(['tax_number', 'line_token', 'signed', 'attach']);
        });
    }
}
