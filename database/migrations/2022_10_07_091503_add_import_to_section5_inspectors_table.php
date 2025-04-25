<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddImportToSection5InspectorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section5_inspectors', function (Blueprint $table) {
            $table->integer('type')->default(1)->comment('ประเภท 1 = ใบสมัคร, 2 = ระบบ Inspectors ');
        });

        Schema::table('section5_inspectors_scopes', function (Blueprint $table) {
            $table->integer('type')->default(1)->comment('ประเภท 1 = ใบสมัคร, 2 = ระบบ Inspectors ');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('section5_inspectors', function (Blueprint $table) {
            $table->dropColumn(['type']);  
        });

        Schema::table('section5_inspectors_scopes', function (Blueprint $table) {
            $table->dropColumn(['type']);  
        });
    }
}
