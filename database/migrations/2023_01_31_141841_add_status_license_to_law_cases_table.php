<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusLicenseToLawCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_cases', function (Blueprint $table) {
            $table->integer('status_license')->nullable()->comment('สถานะ : ดำเนินการใบอนุญาต')->after('offend_zipcode');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_cases', function (Blueprint $table) {
            $table->dropColumn([
                'status_license'
            ]);
        });
    }
}
