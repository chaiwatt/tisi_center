<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBcertifyStandardTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bcertify_standard_type', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title',255)->nullable()->comment('ประเภทมาตรฐาน');
            $table->string('offertype',255)->nullable()->comment('ประเภทข้อมูลเสนอ');
            $table->string('offertype_eng',255)->nullable()->comment('ประเภทข้อมูลเสนอ (Eng)');
            $table->string('department_id',2)->nullable()->comment('กลุ่มผู้ใช้งานที่รับผิดชอบ TB : department');
            $table->integer('state');
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
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
        Schema::dropIfExists('bcertify_standard_type');
    }
}
