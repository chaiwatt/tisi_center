<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStandardSendmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certify_standard_sendmail', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('std_id')->nullable()->comment('id ตาราง certify_standards');
            $table->integer('user_by')->nullable()->comment('id ตาราง sso_users');
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
        Schema::dropIfExists('certify_standard_sendmail');
    }
}
