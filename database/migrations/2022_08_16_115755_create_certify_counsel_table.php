<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCertifyCounselTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certify_counsel', function (Blueprint $table) {
            $table->increments('id');
            $table->string('entrepreneur_name')->nullable()->comment('ผู้ประกอบการ');
            $table->string('contact_name')->nullable()->comment('ชื่อผู้ติดต่อ');
            $table->string('contact_tel')->nullable()->comment('เบอร์โทร ผู้ติดต่อ');
            $table->string('contact_email')->nullable()->comment('E-Mail ผู้ติดต่อ');
            $table->text('feedback')->nullable()->comment('ข้อเสนอแนะ');
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
        Schema::dropIfExists('certify_counsel');
    }
}
