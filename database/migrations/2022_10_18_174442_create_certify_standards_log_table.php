<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCertifyStandardsLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certify_standards_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('std_id')->nullable()->comment('TB : certify_standards . id');
            $table->text('std_full')->nullable()->comment('TB : certify_standards . std_full');
            $table->integer('created_by')->nullable()->comment('ผู้บันทึก');
            $table->integer('updated_by')->nullable()->comment('ผู้แก้ไข');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('certify_standards_log');
    }
}
