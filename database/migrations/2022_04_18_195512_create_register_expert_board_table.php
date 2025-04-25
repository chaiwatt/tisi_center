<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegisterExpertBoardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('register_expert_board', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('expert_id')->comment('Id ตาราง regiter_experts');
            $table->integer('board_type')->comment('Id ตาราง basic_board_types');
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
        Schema::dropIfExists('register_expert_board');
    }
}
