<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttachFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attach_files', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tax_number',50)->nullable()->comment('TB:sso_users.tax_number');
            $table->string('username',50)->nullable()->comment('TB:sso_users.username');
            $table->string('systems',50)->nullable()->comment('ระบบ');
            $table->text('ref_table')->nullable();
            $table->integer('ref_id')->nullable();
            $table->text('url')->nullable();
            $table->text('new_filename')->nullable()->comment('ขื่อไฟล์ใฟม่');
            $table->text('filename')->nullable()->comment('ชื่อไฟล์เดิม');
            $table->integer('size')->nullable()->comment('type ไฟล์');
            $table->text('file_properties')->nullable()->comment('รายละเอียด');
            $table->text('caption')->nullable()->comment('รายละเอียด');
            $table->text('section')->nullable()->comment('เงื่อนไขการแยกไฟล์');
            $table->integer('created_by');
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
        Schema::dropIfExists('attach_files');
    }
}
