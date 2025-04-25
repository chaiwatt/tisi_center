<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationLabSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section5_application_labs_summary', function (Blueprint $table) {
            $table->increments('id');

            $table->date('meeting_date')->nullable()->comment('วันที่ประชุม');
            $table->text('meeting_no')->nullable()->comment('ครั้งที่ประชุม');
            $table->text('meeting_description')->nullable()->comment('รายละเอียด');

            $table->bigInteger('created_by')->nullable()->comment('ผู้บันทึก');
            $table->bigInteger('updated_by')->nullable()->comment('ผู้แก้ไข');

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
        Schema::dropIfExists('section5_application_labs_summary');
    }
}
