<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawBookManagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_book_manage', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('basic_book_group_id')->nullable()->comment('id ตาราง law_basic_book_group');
            $table->integer('basic_book_type_id')->nullable()->comment('id ตาราง law_basic_book_type');
            $table->text('title')->nullable()->comment('ชื่อเรื่อง');
            $table->text('description')->nullable()->comment('คำอธิบาย');
            $table->text('tag')->nullable()->comment('tag (json)');
            $table->text('type_file')->nullable()->comment('รูปแบบไฟล์แนบ(json)');
            $table->text('url')->nullable()->comment('url ที่เกี่ยวข้อง(json)');
            $table->date('date_publish')->nullable()->comment('วันที่เผยแพร่');
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
        Schema::dropIfExists('law_book_manage');
    }
}
