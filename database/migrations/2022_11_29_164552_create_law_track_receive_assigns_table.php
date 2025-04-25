<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawTrackReceiveAssignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_track_receives_assigns', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('law_track_receives_id')->nullable();
            $table->integer('user_id')->nullable()->comment('ผู้รับมอบหมาย runrecno ตาราง user_register');
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->foreign('law_track_receives_id')
                    ->references('id')
                    ->on('law_track_receives')
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
        Schema::dropIfExists('law_track_receives_assigns');
    }
}
