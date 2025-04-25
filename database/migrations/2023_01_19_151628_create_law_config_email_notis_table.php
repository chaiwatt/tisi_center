<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawConfigEmailNotisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_config_email_notis', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title',255)->nullable()->comment('ชื่อหัวข้อ');
            $table->text('email_list')->nullable()->comment('email เก็บเป็๋น json');
            $table->integer('created_by')->unsigned();
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('law_config_email_notis');
    }
}
