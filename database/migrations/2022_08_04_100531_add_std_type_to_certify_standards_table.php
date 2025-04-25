<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStdTypeToCertifyStandardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('certify_standards', function (Blueprint $table) {
            $table->integer('std_type')->nullable()->after('setstandard_id')->comment('ประเภทมาตรฐาน TB: bcertify_standard_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('certify_standards', function (Blueprint $table) {
            $table->dropColumn(['std_type']);
        });
    }
}
