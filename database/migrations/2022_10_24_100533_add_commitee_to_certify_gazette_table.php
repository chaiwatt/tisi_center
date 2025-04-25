<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCommiteeToCertifyGazetteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('certify_gazette', function (Blueprint $table) {
            $table->string('committee')->nullable()->comment('เจ้าของเรื่อง');
            $table->string('gaz_page')->nullable()->comment('จำนวนหน้า');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('certify_gazette', function (Blueprint $table) {
            $table->dropColumn(['committee','gaz_page']);  
        });
    }
}
