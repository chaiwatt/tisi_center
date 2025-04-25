<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawLogSendmailSuccessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_log_sendmail_successe', function (Blueprint $table) {
            $table->increments('id');
            $table->text('ref_table')->nullable()->comment('ชื่อตาราง'); 
            $table->integer('ref_id')->nullable()->comment('id ตาราง');
            $table->integer('state')->nullable()->comment('1.successe 2.error');
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
        Schema::dropIfExists('law_log_sendmail_successe');
    }
}
