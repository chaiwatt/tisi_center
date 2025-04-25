<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddResponseEmailToLawCasesDeliveryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_cases_delivery', function (Blueprint $table) {
            $table->text('response_email')->nullable()->comment('ตอบกลับ : เบอร์โทร')->after('response_tel');
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
            $table->dropColumn(['response_email']);
        });
    }
}
