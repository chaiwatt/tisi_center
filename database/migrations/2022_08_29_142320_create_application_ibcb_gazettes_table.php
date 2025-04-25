<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationIbcbGazettesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section5_application_ibcb_gazettes', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('application_id')->nullable();
            $table->string('application_no')->nullable()->comment('เลขที่อ้างอิงคำขอ');

            $table->string('issue')->nullable()->comment('ฉบับที่');
            $table->string('year')->nullable()->comment('ปีที่ประกาศ');
            $table->date('announcement_date')->nullable()->comment('ประกาศ ณ วันที่');
            $table->string('sign_id')->nullable()->comment('ผู้ลงนาม');
            $table->text('sign_name')->nullable()->comment('ชื่อผู้ลงนาม');
            $table->text('sign_position')->nullable()->comment('ตำแหน่ง');
            $table->integer('created_by')->nullable()->comment('ผู้บันทึก');
            $table->integer('updated_by')->nullable()->comment('ผู้แก้ไข');

            $table->timestamps();

            $table->foreign('application_id')
                    ->references('id')
                    ->on('section5_application_ibcb')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('section5_application_ibcb_gazettes');
    }
}
