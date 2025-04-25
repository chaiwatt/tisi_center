<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentSystemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sso_agent_systems', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('setting_systems_id')->nullable()->comment('ระบบที่มอบอำนาจ ตาราง setting_systems');
            $table->unsignedInteger('sso_agent_id');
            $table->foreign('sso_agent_id')
                  ->references('id')
                  ->on('sso_agent')
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
        Schema::dropIfExists('sso_agent_systems');
    }
}
