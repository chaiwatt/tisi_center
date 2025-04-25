<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTisStandardsTisTisnoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tis_standards', function (Blueprint $table) {
            $table->string('tis_tisshortno',255)->nullable()->comment('เลข มอก. (แบบย่อ)')->after('tis_tisno');
            $table->integer('publishing_status')->nullable()->comment('สถานะเผยแพร่')->after('state');
            $table->integer('tisid_ref')->nullable()->comment('อ้างอิงเลข มอก. ที่ทบทวน')->after('tis_tisshortno');
            $table->integer('tisno_ref')->nullable()->comment('id ของ มอก. ที่ทบทวน')->after('tisid_ref');
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
            $table->dropColumn(['tis_tisshortno','publishing_status','tisid_ref','tisno_ref']);
        });
    }
}
