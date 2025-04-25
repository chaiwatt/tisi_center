<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStdSignnameToCertifyStandardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('certify_standards', function (Blueprint $table) {
            $table->integer('std_signname')->nullable()->comment('ตารางผู้ลงนาม')->after('std_sign_date');
            $table->string('std_signposition')->nullable()->comment('ตำแหน่งผู้ลงนาม')->after('std_signname');
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
            $table->dropColumn(['std_signname','std_signposition']);  
        });
    }
}
