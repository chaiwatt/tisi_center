<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawListenMinistryResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_listen_ministry_results', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('listen_id')->nullable()->comment('id ตาราง law_listen_ministry');   
            $table->date('date_on')->nullable()->comment('ลงวันที่');
            $table->date('date_effective')->nullable()->comment('วันที่มีผลบังคับใช้');
            $table->date('date_announcement')->nullable()->comment('วันที่ประกาศ');
            $table->string('book')->nullable()->comment('เล่ม');
            $table->string('section')->nullable()->comment('ตอนที่');
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
        Schema::dropIfExists('law_listen_ministry_results');
    }
}
