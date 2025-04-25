<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBcertifySettingRunningsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bcertify_setting_runnings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('system')->nullable()->comment('ระบบงาน');
            $table->string('system_en')->nullable()->comment('ระบบงาน (ภาษาอังกฤษ)');
            $table->tinyInteger('state')->nullable()->comment('สถานะ 1 = Active, 0 = Not Active');
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
        Schema::dropIfExists('bcertify_setting_runnings');
    }
}
