<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_basic_section', function (Blueprint $table) {
            $table->increments('id');
            $table->string('number')->nullable()->comment('เลขมาตรา');
            $table->text('title')->nullable()->comment('คำอธิบายมาตรา');        
            $table->text('remark')->nullable()->comment('หมายเหตุ');
            $table->date('start_date')->nullable()->comment('วันที่เริ่มใช้งาน');
            $table->date('end_date')->nullable()->comment('วันที่สิ้นสุดใช้งาน');
            $table->boolean('state')->nullable();
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
        Schema::drop('law_basic_section');
    }
}
