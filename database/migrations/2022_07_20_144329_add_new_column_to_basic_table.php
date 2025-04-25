<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnToBasicTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('province', function (Blueprint $table) {
            $table->text('PROVINCE_NAME_EN')->nullable();
        });

        Schema::table('amphur', function (Blueprint $table) {
            $table->text('AMPHUR_NAME_EN')->nullable();
            
        });

        Schema::table('district', function (Blueprint $table) {
            $table->text('DISTRICT_NAME_EN')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('province', function (Blueprint $table) {
            $table->dropColumn(['PROVINCE_NAME_EN']);
        });

        Schema::table('amphur', function (Blueprint $table) {
            $table->dropColumn(['AMPHUR_NAME_EN' ]);
        });

        Schema::table('district', function (Blueprint $table) {
            $table->dropColumn(['DISTRICT_NAME_EN']);
        });
    }
}
