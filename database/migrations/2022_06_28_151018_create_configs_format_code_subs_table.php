<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfigsFormatCodeSubsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configs_format_codes_sub', function (Blueprint $table) {
            $table->string('format')->nullable()->comment('รูปแบบ');
            $table->string('data')->nullable();
            $table->string('sub_data')->nullable();
            $table->unsignedInteger('format_id')->nullable();
            $table->timestamps();
            $table->foreign('format_id')
                    ->references('id')
                    ->on('configs_format_codes')
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
        Schema::dropIfExists('configs_format_codes_sub');
    }
}
