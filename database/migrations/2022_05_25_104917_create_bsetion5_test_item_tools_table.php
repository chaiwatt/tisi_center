<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBsetion5TestItemToolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bsection5_test_item_tools', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('bsection5_test_item_id')->nullable();
            $table->integer('test_tools_id')->nullable()->comment('เครื่องมือทดสอบ');
            $table->timestamps();
            $table->foreign('bsection5_test_item_id')
                    ->references('id')
                    ->on('bsection5_test_item')
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
        Schema::dropIfExists('bsection5_test_item_tools');
    }
}
