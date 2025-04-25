<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTisiEstandardOffersStandardTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tisi_estandard_offers', function (Blueprint $table) {
            $table->integer('standard_types')->nullable()->comment('ประเภทมาตรฐาน :  1.มอก. , 2.มอก.เอส , 3.มตช. , 4.มตช./ข้อกำหนดเผยแพร่ , 5.ข้อตกลงร่วม , 6.มผช.');
            $table->text('details')->nullable()->comment('รายละเอียด');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tisi_estandard_offers', function (Blueprint $table) {
            $table->dropColumn(['standard_types','details']);
        });
    }
}
