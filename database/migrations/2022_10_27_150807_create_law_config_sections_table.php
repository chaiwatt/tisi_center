<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLawConfigSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_config_section', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('section_id')->nullable()->comment('id ตาราง law_basic_section');
            $table->integer('power')->nullable()->comment('1=เลขาธิการสำนักงานมาตรฐานอุตสาหกรรม(สมอ) 2=คณะกรรมการเปรียบเทียบ ');
            $table->text('section_relation')->nullable()->comment('มาตราที่เกี่ยวข้อง tb:law_basic_section');
            $table->boolean('state')->nullable();
            $table->timestamps();
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('law_config_section');
    }
}
