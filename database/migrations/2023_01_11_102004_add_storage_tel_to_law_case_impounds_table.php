<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStorageTelToLawCaseImpoundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_case_impounds', function (Blueprint $table) {
            $table->string('storage_tel',50)->nullable()->comment('จัดเก็บผลิตภัณฑ์ : เบอร์โทร')->after('storage_zipcode');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_case_impounds', function (Blueprint $table) {
            $table->dropColumn(['storage_tel']);
        });
    }
}
