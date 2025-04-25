<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToLawCasesDeliveryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_cases_delivery', function (Blueprint $table) {
            $table->text('response_date')->after('response_tel')->nullable()->comment('ตอบกลับ : วันที่');
            $table->boolean('status')->nullable()->comment('สถานะ');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_cases_delivery', function (Blueprint $table) {
            $table->dropColumn(['response_date','status']);
            
        });
    }
}
