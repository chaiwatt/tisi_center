<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawListenMinistryTracksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_listen_ministry_track', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('listen_id')->nullable()->comment('id ตาราง law_listen_ministry');   
            $table->date('date_track')->nullable()->comment('วันที่ติดตาม');
            $table->date('date_due')->nullable()->comment('วันครบกำหนด');
            $table->integer('status_id')->nullable()->comment('สถานะการดำเนินงาน'); 
            $table->text('detail')->nullable()->comment('รายละเอียด');
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
        Schema::dropIfExists('law_listen_ministry_track');
    }
}
