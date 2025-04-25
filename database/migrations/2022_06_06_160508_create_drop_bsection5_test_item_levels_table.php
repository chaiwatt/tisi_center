<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDropBsection5TestItemLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('bsection5_test_item_levels');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('bsection5_test_item_levels', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('bsection5_test_item_id')->nullable();

            $table->integer('test_item_id')->nullable()->comment('ID ตาราง bsection5_test_item');
            $table->integer('level')->nullable()->comment('ระดับ');

            $table->timestamps();
            $table->foreign('bsection5_test_item_id')
                    ->references('id')
                    ->on('bsection5_test_item')
                    ->onDelete('cascade');
        });
    }
}
