<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApiSettingsApiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_settings_api', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 255)->comment('รหัส API');
            $table->string('name', 255)->comment('ชื่อ API');
            $table->text('url')->nullable()->comment('URL ที่เรียกใช้ API');
            $table->text('dynamic_param')->nullable()->comment('ค่าจากฟอร์มที่ต้องส่งไป');
            $table->text('static_param')->nullable()->comment('ค่าคงที่ที่ต้องส่งไป');
            $table->integer('status')->comment('ค่าคงที่ที่ต้องส่งไป');
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
        Schema::dropIfExists('api_settings_api');
    }
}
