<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddListenTypeToLawListenMinistryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_listen_ministry', function (Blueprint $table) {
            $table->integer('listen_type')->nullable()->comment('id ตาราง law_basic_listen_type')->after('title');
            $table->text('url')->nullable()->comment('ลิงค์')->after('url_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_listen_ministry', function (Blueprint $table) {
            $table->dropColumn(['listen_type','url']);
        });
    }
}
