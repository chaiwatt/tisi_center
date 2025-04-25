<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSetStandardSummeetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certify_setstandard_summeetings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('setstandard_id')->nullable()->comment('id ตาราง certify_setstandards');
            $table->integer('amount_sum')->nullable()->comment('จำนวนครั้งการประชุมทั้งหมด');
            $table->decimal('cost_sum',10,2)->nullable()->comment('ค่าใช้จ่ายในการประชุมทั้งหมด');
            $table->text('detail')->nullable()->comment('รายละเอียด');
            $table->text('attach')->nullable()->comment('เอกสารที่เกี่ยวข้อง');
            $table->text('responsible_by')->nullable()->comment('เจ้าหน้าที่ ที่รับผิดชอบ');
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
        Schema::dropIfExists('certify_setstandard_summeetings');
    }
}
