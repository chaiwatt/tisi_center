<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddImportToSection5IbcbsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section5_ibcbs', function (Blueprint $table) {
            $table->integer('type')->default(1)->comment('ประเภท 1 = ใบสมัคร, 2 = ระบบ IBCB ');
        });

        Schema::table('section5_ibcbs_inspectors', function (Blueprint $table) {
            $table->integer('type')->default(1)->comment('ประเภท 1 = ใบสมัคร, 2 = ระบบ IBCB ');
        });

        Schema::table('section5_ibcbs_scopes', function (Blueprint $table) {
            $table->integer('type')->default(1)->comment('ประเภท 1 = ใบสมัคร, 2 = ระบบ IBCB ');
        });

        Schema::table('section5_ibcbs_scopes_details', function (Blueprint $table) {
            $table->integer('type')->default(1)->comment('ประเภท 1 = ใบสมัคร, 2 = ระบบ IBCB ');
        });

        Schema::table('section5_ibcbs_scopes_tis', function (Blueprint $table) {
            $table->integer('type')->default(1)->comment('ประเภท 1 = ใบสมัคร, 2 = ระบบ IBCB ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('section5_ibcbs', function (Blueprint $table) {
            $table->dropColumn(['type']);  
        });

        Schema::table('section5_ibcbs_inspectors', function (Blueprint $table) {
            $table->dropColumn(['type']);  
        });

        Schema::table('section5_ibcbs_scopes', function (Blueprint $table) {
            $table->dropColumn(['type']);  
        });

        Schema::table('section5_ibcbs_scopes_details', function (Blueprint $table) {
            $table->dropColumn(['type']);  
        });

        Schema::table('section5_ibcbs_scopes_tis', function (Blueprint $table) {
            $table->dropColumn(['type']);  
        });
    }
}
