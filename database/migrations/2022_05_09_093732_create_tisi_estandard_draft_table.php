<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTisiEstandardDraftTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tisi_estandard_draft', function (Blueprint $table) {
            $table->increments('id');
            $table->string('draft_year',255)->nullable()->comment('ปีที่ทำร่างแผน');
            $table->string('status_id',255)->nullable()->comment('สถานะ (1.-ร่างมาตรฐาน, 2.-เห็นชอบร่างมาตรฐาน, 3.-ไม่เห็นชอบร่างมาตรฐาน)');
            $table->integer('created_by')->nullable();
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
        Schema::dropIfExists('tisi_estandard_draft');
    }
}
