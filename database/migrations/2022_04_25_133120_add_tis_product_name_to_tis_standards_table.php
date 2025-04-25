<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTisProductNameToTisStandardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tis_standards', function (Blueprint $table) {
            $table->text('tis_product_name')->nullable()->comment('ชื่อผลิตภัณฑ์อุตสาหกรรม');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tis_standards', function (Blueprint $table) {
            $table->dropColumn(['tis_product_name']);
        });
    }
}
