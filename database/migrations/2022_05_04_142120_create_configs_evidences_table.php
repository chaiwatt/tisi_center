<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfigsEvidencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configs_evidences', function (Blueprint $table) {

            $table->increments('id');
            $table->unsignedInteger('evidence_group_id')->nullable();
            $table->text('title')->nullable()->comment('ชื่อเอกสาร');
            $table->boolean('required')->nullable()->comment('บังคับ');
            $table->text('caption')->nullable()->comment('รายละเอียด');
            $table->string('file_properties')->nullable()->comment('ประเภทไฟล์');
            $table->text('size')->nullable()->comment('ขนาดไฟล์');
            $table->boolean('state')->nullable();
            $table->integer('created_by')->unsigned();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->foreign('evidence_group_id')
                    ->references('id')
                    ->on('configs_evidence_groups')
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
        Schema::dropIfExists('configs_evidences');
    }
}
