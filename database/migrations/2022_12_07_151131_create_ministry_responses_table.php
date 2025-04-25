<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMinistryResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_listen_ministry_responses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('listen_id')->nullable()->comment('id ตาราง law_listen_ministry');   
            $table->integer('comment_point')->nullable()->comment('ข้อคิดเห็น 1=เห็นชอบตามแบบร่างมาตรฐานฯ ทุกประการ, 2=เห็นชอบตามแบบร่างมาตรฐานฯ  เป็นส่วนใหญ่ แต่มีข้อคิดเห็นเพิ่มเติม, 3=ไม่เห็นชอบตามแบบร่างมาตรฐานฯ เนื่องจากมีประเด็นทางวิชาการ,4=ไม่เห็นชอบตามแบบร่างมาตรฐานฯ เนื่องจากมีประเด็นอื่น ๆ');   
            $table->text('comment_more')->nullable()->comment('ข้อคิดเห็นเพิ่มเติม');
            $table->integer('trader_type')->nullable()->comment('ประเภทสถานประกอบการ 1=นิติบุคคล, 2=บุคคลธรรมดา, 3=อื่นๆ (ระบุ)');   
            $table->string('trader_other')->nullable()->comment('ประเภทสถานประกอบการอื่นๆ (ระบุ)');
            $table->string('tax_number')->nullable()->comment('เลขที่บัตรประชาชน / Passport');      
            $table->string('name',255)->nullable()->comment('ชื่อ-สกุล/ชื่อบริษัทฯ');        
            $table->string('agency',255)->nullable()->comment('สังกัด / หน่วยงาน');
            $table->string('position',255)->nullable()->comment('ตำแหน่ง');
            $table->string('address',255)->nullable()->comment('ที่อยู่');
            $table->string('tel',255)->nullable()->comment('เบอร์โทรศัพท์');
            $table->string('email',255)->nullable()->comment('e-Mail');
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
        Schema::dropIfExists('law_listen_ministry_responses');
    }
}
