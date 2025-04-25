<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBcertifySettingRunningSubsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bcertify_setting_running_subs', function (Blueprint $table) {
            $table->string('format')->nullable()->comment('รูปแบบ');
            $table->string('data')->nullable();
            $table->string('sub_data')->nullable();
            $table->unsignedInteger('format_id')->nullable();
            $table->timestamps();
            $table->foreign('format_id')
                    ->references('id')
                    ->on('bcertify_setting_runnings')
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
        Schema::dropIfExists('bcertify_setting_running_subs');
    }
}
