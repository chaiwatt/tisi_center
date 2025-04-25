<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTb4TisilicenseChangeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tb4_tisilicense_change', function (Blueprint $table) {
            $table->string('created_by',255)->nullable()->comment('ผู้บันทึก')->change();
            $table->string('updated_by',255)->nullable()->comment('ผู้อัพเดพ')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tb4_tisilicense_change', function (Blueprint $table) {
            $table->integer('created_by')->nullable()->comment('ผู้บันทึก')->change();
            $table->integer('updated_by')->nullable()->comment('ผู้อัพเดพ')->change();
        });
    }
}
