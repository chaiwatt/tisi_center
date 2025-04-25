<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOtherToLawBasicDepartmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_basic_department', function (Blueprint $table) {
            $table->boolean('other')->nullable()->comment('สถานะ 1 = ต้องกรอกtext/อื่นๆ')->after('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_basic_department', function (Blueprint $table) {
            $table->dropColumn('other');
        });
    }
}
